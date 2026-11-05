<?php

namespace Tests\Unit;

use App\Support\EncryptedBackup;
use App\Support\TimeBasedOneTimePassword;
use RuntimeException;
use Tests\TestCase;

class EncryptedBackupTest extends TestCase
{
    public function test_encrypted_backup_restores_binary_data_without_exposing_plaintext(): void
    {
        $directory = sys_get_temp_dir().'/cinehub-security-'.bin2hex(random_bytes(5));
        mkdir($directory, 0700);
        $source = $directory.'/input';
        $encrypted = $directory.'/backup.chbk';
        $restored = $directory.'/output';
        $contents = str_repeat(random_bytes(256), 800);
        try {
            file_put_contents($source, $contents);
            EncryptedBackup::encrypt($source, $encrypted);
            $this->assertStringNotContainsString(substr($contents, 0, 32), file_get_contents($encrypted));
            EncryptedBackup::decrypt($encrypted, $restored);
            $this->assertSame($contents, file_get_contents($restored));
        } finally {
            foreach ([$source, $encrypted, $restored] as $file) @unlink($file);
            rmdir($directory);
        }
    }

    public function test_corrupt_backup_fails_authenticated_decryption(): void
    {
        $directory = sys_get_temp_dir().'/cinehub-security-'.bin2hex(random_bytes(5));
        mkdir($directory, 0700);
        $source = $directory.'/input';
        $encrypted = $directory.'/backup.chbk';
        $restored = $directory.'/output';
        try {
            file_put_contents($source, 'sensitive content');
            EncryptedBackup::encrypt($source, $encrypted);
            $bytes = file_get_contents($encrypted);
            $bytes[40] = chr(ord($bytes[40]) ^ 1);
            file_put_contents($encrypted, $bytes);
            $this->expectException(RuntimeException::class);
            EncryptedBackup::decrypt($encrypted, $restored);
        } finally {
            foreach ([$source, $encrypted, $restored] as $file) @unlink($file);
            rmdir($directory);
        }
    }

    public function test_generated_authenticator_secret_has_expected_format(): void
    {
        $secret = TimeBasedOneTimePassword::generateSecret();
        $this->assertMatchesRegularExpression('/^[A-Z2-7]{32}$/', $secret);
        $this->assertNull(TimeBasedOneTimePassword::matchingStep($secret, 'bad'));
    }
}
