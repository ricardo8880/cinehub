<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieConsentController extends Controller
{
    public function update(Request $request, string $locale): RedirectResponse
    {
        $data = $request->validate(['analytics' => ['required', 'in:granted,denied']]);

        Cookie::queue(cookie('cinehub_analytics_consent', $data['analytics'], 60 * 24 * 180, '/', null, $request->isSecure(), false, false, 'Lax'));

        if ($data['analytics'] === 'granted') {
            if (! preg_match('/\A[a-f0-9]{32}\z/D', (string) $request->cookie('cinehub_analytics_visitor'))) {
                Cookie::queue(cookie('cinehub_analytics_visitor', bin2hex(random_bytes(16)), 60 * 24 * 180, '/', null, $request->isSecure(), true, false, 'Lax'));
            }
        } else {
            $request->session()->forget('cinehub_page_engagement_visits');
            Cookie::queue(Cookie::forget('cinehub_analytics_visitor'));
        }

        return back()->with('success', __('Privacy preference saved.'));
    }
}
