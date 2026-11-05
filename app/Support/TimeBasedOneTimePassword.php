<?php

namespace App\Support;

/** RFC 6238 (HMAC-SHA1, 30-second steps) compatible with authenticator apps. */
final class TimeBasedOneTimePassword
{
    public static function generateSecret(): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $bytes = random_bytes(20);
        $bits = '';

        foreach (str_split($bytes) as $byte) {
            $bits .= str_pad(decbin(ord($byte)), 8, '0', STR_PAD_LEFT);
        }

        $secret = '';
        foreach (str_split($bits, 5) as $chunk) {
            $secret .= $alphabet[bindec($chunk)];
        }

        return $secret;
    }

    public static function matchingStep(string $secret, string $code, ?int $timestamp = null): ?int
    {
        if (! preg_match('/^\d{6}$/D', $code) || ! preg_match('/^[A-Z2-7]{32}$/D', $secret)) {
            return null;
        }

        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $bits = '';
        foreach (str_split($secret) as $char) {
            $bits .= str_pad(decbin(strpos($alphabet, $char)), 5, '0', STR_PAD_LEFT);
        }
        $key = '';
        foreach (str_split($bits, 8) as $chunk) {
            if (strlen($chunk) === 8) {
                $key .= chr(bindec($chunk));
            }
        }

        $currentStep = intdiv($timestamp ?? time(), 30);
        foreach ([$currentStep, $currentStep - 1, $currentStep + 1] as $step) {
            if ($step < 0) {
                continue;
            }
            $counter = pack('N2', $step >> 32, $step & 0xffffffff);
            $hash = hash_hmac('sha1', $counter, $key, true);
            $offset = ord($hash[19]) & 0x0f;
            $number = (unpack('N', substr($hash, $offset, 4))[1] & 0x7fffffff) % 1000000;
            if (hash_equals(str_pad((string) $number, 6, '0', STR_PAD_LEFT), $code)) {
                return $step;
            }
        }

        return null;
    }
}
