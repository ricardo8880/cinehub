<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{
    protected $signature = 'cinehub:backup';
    protected $description = 'Create a local backup of the SQLite database used by CineHub.';

    public function handle(): int
    {
        if (config('database.default') !== 'sqlite') {
            $this->warn('Automatic backup is configured only for SQLite. Use the hosting/database provider backup in production.');
            return self::SUCCESS;
        }

        $database = config('database.connections.sqlite.database');
        if (! is_string($database) || ! File::exists($database)) {
            $this->error('SQLite database file was not found.');
            return self::FAILURE;
        }

        $directory = storage_path('app/backups');
        File::ensureDirectoryExists($directory);
        $destination = $directory.'/database-'.now()->format('Y-m-d-His').'.sqlite';
        File::copy($database, $destination);

        $this->info("Backup created: {$destination}");
        return self::SUCCESS;
    }
}
