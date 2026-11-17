<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class VisitorIdentity
{
    public function key(Request $request): string
    {
        $secret = (string) config('app.key', config('app.name'));
        $sessionId = $request->hasSession() ? $request->session()->getId() : '';
        $userAgent = substr((string) $request->userAgent(), 0, 512);

        return hash_hmac('sha256', $sessionId.'|'.$userAgent, $secret);
    }

    public function analyticsAllowed(Request $request): bool
    {
        return $request->cookie('cinehub_analytics_consent') === 'granted';
    }

    public function analyticsKey(Request $request): string
    {
        $secret = (string) config('app.key', config('app.name'));

        if ($request->user()) {
            return hash_hmac('sha256', 'user:'.$request->user()->getAuthIdentifier(), $secret);
        }

        // An analytics identifier is issued only after explicit consent.
        // No IP or raw visitor agent is persisted in the analytics tables.
        $id = (string) $request->cookie('cinehub_analytics_visitor');
        if (! preg_match('/\A[a-f0-9]{32}\z/D', $id)) {
            $id = bin2hex(random_bytes(16));
            Cookie::queue(cookie('cinehub_analytics_visitor', $id, 60 * 24 * 180, '/', null, $request->isSecure(), true, false, 'Lax'));
        }

        return hash_hmac('sha256', 'guest:'.$id, $secret);
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
