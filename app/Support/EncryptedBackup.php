<?php

namespace App\Support;

use RuntimeException;

/** Authenticated, chunked encryption for database backups (AES-256-GCM). */
final class EncryptedBackup
{
    private const MAGIC = "CHBK1";
    private const CHUNK_SIZE = 65536;

    public static function encrypt(string $source, string $destination): void
    {
        $input = fopen($source, 'rb');
        $output = fopen($destination, 'xb');
        if (! $input || ! $output) {
            if (is_resource($input)) fclose($input);
            if (is_resource($output)) fclose($output);
            throw new RuntimeException('Could not open backup files for encryption.');
        }

        try {
            chmod($destination, 0600);
            self::write($output, self::MAGIC);
            $index = 0;
            $key = self::key();

            while (! feof($input)) {
                $plain = fread($input, self::CHUNK_SIZE);
                if ($plain === false) throw new RuntimeException('Could not read the database backup.');
                if ($plain === '') break;
                self::writeRecord($output, $plain, $key, $index++, false);
            }

            // An authenticated terminator prevents undetected truncation.
            self::writeRecord($output, '', $key, $index, true);
        } catch (\Throwable $exception) {
            fclose($input);
            fclose($output);
            @unlink($destination);
            throw $exception;
        }

        fclose($input);
        fclose($output);
    }

    public static function decrypt(string $source, string $destination): void
    {
        $input = fopen($source, 'rb');
        $output = fopen($destination, 'xb');
        if (! $input || ! $output) {
            if (is_resource($input)) fclose($input);
            if (is_resource($output)) fclose($output);
            throw new RuntimeException('Could not open backup files for decryption.');
        }

        try {
            chmod($destination, 0600);
            if (self::readExactly($input, strlen(self::MAGIC)) !== self::MAGIC) {
                throw new RuntimeException('Unrecognized backup format.');
            }

            $key = self::key();
            $index = 0;
            while (true) {
                $lengthBytes = self::readExactly($input, 4);
                $length = unpack('N', $lengthBytes)[1];
                if ($length > self::CHUNK_SIZE) throw new RuntimeException('Invalid encrypted backup chunk size.');

                $iv = self::readExactly($input, 12);
                $tag = self::readExactly($input, 16);
                $cipher = self::readExactly($input, $length);
                $isLast = $length === 0;
                $aad = pack('N2', $index >> 32, $index & 0xffffffff).($isLast ? 'E' : 'D');
                $plain = openssl_decrypt($cipher, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag, $aad);
                if ($plain === false) throw new RuntimeException('Backup authentication failed (incorrect key or modified file).');
                if ($isLast) {
                    if (! feof($input) && fread($input, 1) !== '') {
                        throw new RuntimeException('Trailing data after encrypted backup.');
                    }
                    break;
                }
                self::write($output, $plain);
                $index++;
            }
        } catch (\Throwable $exception) {
            fclose($input);
            fclose($output);
            @unlink($destination);
            throw $exception;
        }

        fclose($input);
        fclose($output);
    }

    private static function writeRecord($output, string $plain, string $key, int $index, bool $last): void
    {
        $iv = random_bytes(12);
        $aad = pack('N2', $index >> 32, $index & 0xffffffff).($last ? 'E' : 'D');
        $cipher = openssl_encrypt($plain, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag, $aad);
        if ($cipher === false) throw new RuntimeException('Backup encryption failed.');
        self::write($output, pack('N', strlen($cipher)).$iv.$tag.$cipher);
    }

    private static function readExactly($stream, int $size): string
    {
        $buffer = '';
        while (strlen($buffer) < $size) {
            $chunk = fread($stream, $size - strlen($buffer));
            if ($chunk === '' || $chunk === false) {
                throw new RuntimeException('Backup file is truncated.');
            }
            $buffer .= $chunk;
        }
        return $buffer;
    }

    private static function write($stream, string $data): void
    {
        while ($data !== '') {
            $written = fwrite($stream, $data);
            if ($written === false || $written === 0) throw new RuntimeException('Could not write backup data.');
            $data = substr($data, $written);
        }
    }

    private static function key(): string
    {
        $value = (string) config('app.key');
        $raw = str_starts_with($value, 'base64:')
            ? base64_decode(substr($value, 7), true)
            : $value;
        if (! is_string($raw) || strlen($raw) < 32) {
            throw new RuntimeException('A valid APP_KEY is required to encrypt or decrypt backups.');
        }

        return hash_hkdf('sha256', $raw, 32, 'cinehub-database-backup-v1');
    }
}
