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

        return back()->with('success', __('Privacy preference saved.'));
    }
}
