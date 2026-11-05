<?php

namespace App\Console\Commands;

use App\Support\EncryptedBackup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    protected $signature = 'cinehub:backup';
    protected $description = 'Back up SQLite or MySQL/MariaDB to encrypted storage outside the public directory.';

    public function handle(): int
    {
        $driver = config('database.default');
        if (! in_array($driver, ['mysql', 'mariadb', 'sqlite'], true)) {
            $this->error('This database driver is not supported for backups.');
            return self::FAILURE;
        }

        $directory = storage_path('app/private/backups');
        File::ensureDirectoryExists($directory, 0700, true);
        @chmod($directory, 0700);
        $extension = $driver === 'sqlite' ? 'sqlite' : 'sql';
        $suffix = now()->format('Y-m-d-His').'-'.bin2hex(random_bytes(3));
        $temporary = $directory.'/tmp-'.$suffix.'.'.$extension;
        $destination = $directory.'/database-'.$suffix.'.'.$extension.'.chbk';

        try {
            if ($driver === 'sqlite') {
                $database = config('database.connections.sqlite.database');
                if (! is_string($database) || $database === ':memory:' || ! File::exists($database)) {
                    throw new \RuntimeException('SQLite database file was not found.');
                }

                // VACUUM INTO creates a consistent snapshot even while the app writes.
                $quoted = str_replace("'", "''", $temporary);
                DB::connection('sqlite')->getPdo()->exec("VACUUM INTO '{$quoted}'");
            } else {
                $connection = config('database.connections.'.$driver);
                if (! is_array($connection) || empty($connection['database'])) {
                    throw new \RuntimeException('MySQL/MariaDB configuration is incomplete.');
                }

                $args = [
                    'mysqldump', '--single-transaction', '--quick', '--skip-lock-tables',
                    '--no-tablespaces', '--default-character-set=utf8mb4',
                    '--host='.(string) ($connection['host'] ?? '127.0.0.1'),
                    '--port='.(string) ($connection['port'] ?? 3306),
                    '--user='.(string) ($connection['username'] ?? ''),
                    '--result-file='.$temporary,
                ];
                if (! empty($connection['unix_socket'])) {
                    $args[] = '--socket='.$connection['unix_socket'];
                }
                $args[] = (string) $connection['database'];

                // Never place the password on the process command line or in logs.
                $process = new Process($args, base_path(), ['MYSQL_PWD' => (string) ($connection['password'] ?? '')]);
                $process->setTimeout(600);
                $process->run();
                if (! $process->isSuccessful()) {
                    throw new \RuntimeException('mysqldump failed: '.substr(trim($process->getErrorOutput()), 0, 350));
                }
            }

            if (! File::exists($temporary) || File::size($temporary) === 0) {
                throw new \RuntimeException('The database backup is empty.');
            }
            @chmod($temporary, 0600);
            EncryptedBackup::encrypt($temporary, $destination);
            $this->removeExpiredBackups($directory);
            $this->info('Encrypted backup created: '.$destination);
            Log::info('CineHub database backup created.', ['file' => basename($destination)]);

            return self::SUCCESS;
        } catch (\Throwable $exception) {
            @unlink($destination);
            $this->error('Backup failed: '.$exception->getMessage());
            Log::error('CineHub database backup failed.', [
                'driver' => $driver, 'error' => $exception->getMessage(),
            ]);

            return self::FAILURE;
        } finally {
            // Unencrypted SQL/SQLite is never retained as a backup artifact.
            @unlink($temporary);
        }
    }

    private function removeExpiredBackups(string $directory): void
    {
        $days = max(1, (int) config('site.backup_retention_days', 14));
        $cutoff = now()->subDays($days)->timestamp;
        foreach (File::files($directory) as $file) {
            if (str_starts_with($file->getFilename(), 'database-')
                && str_ends_with($file->getFilename(), '.chbk')
                && $file->getMTime() < $cutoff) {
                File::delete($file->getPathname());
            }
        }
    }
}
