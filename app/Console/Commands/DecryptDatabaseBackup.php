<?php

namespace App\Console\Commands;

use App\Support\EncryptedBackup;
use Illuminate\Console\Command;

class DecryptDatabaseBackup extends Command
{
    protected $signature = 'cinehub:backup:decrypt {source : Encrypted .chbk file} {destination : New .sql or .sqlite file}';
    protected $description = 'Decrypt a database backup with the original APP_KEY; does not change the database.';

    public function handle(): int
    {
        $source = (string) $this->argument('source');
        $destination = (string) $this->argument('destination');
        if (! is_file($source) || file_exists($destination)) {
            $this->error('Source missing or destination already exists.');
            return self::FAILURE;
        }
        try {
            EncryptedBackup::decrypt($source, $destination);
            $this->info('Backup decrypted: '.$destination);
            $this->warn('This file is unencrypted. Securely delete it after restoring your database.');
            return self::SUCCESS;
        } catch (\Throwable $exception) {
            $this->error('Decryption failed: '.$exception->getMessage());
            return self::FAILURE;
        }
    }
}
