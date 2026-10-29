<?php

namespace App\Support;

use Illuminate\Http\Request;

class VisitorIdentity
{
    public function key(Request $request): string
    {
        $secret = (string) config('app.key', config('app.name'));
        $sessionId = $request->hasSession() ? $request->session()->getId() : '';
        $userAgent = substr((string) $request->userAgent(), 0, 512);

        return hash_hmac('sha256', $sessionId.'|'.$userAgent, $secret);
    }

    public function isBot(Request $request): bool
    {
        $userAgent = strtolower((string) $request->userAgent());

        if ($userAgent === '') {
            return true;
        }

        return (bool) preg_match('/bot|crawl|spider|slurp|bingpreview|facebookexternalhit|linkedinbot|twitterbot|discordbot|whatsapp|telegrambot/i', $userAgent);
    }
}
