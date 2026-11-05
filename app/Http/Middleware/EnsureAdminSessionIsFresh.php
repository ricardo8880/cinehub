<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminSessionIsFresh
{
    private const IDLE_TIMEOUT_SECONDS = 20 * 60;

    public function handle(Request $request, Closure $next): Response
    {
        $lastActivity = $request->session()->get('admin.last_activity');

        if ((! app()->environment('local') && ! $request->user('admin')?->admin_two_factor_secret)
            || ! is_int($lastActivity)
            || time() - $lastActivity > self::IDLE_TIMEOUT_SECONDS
            || $lastActivity > time() + 30) {
            Auth::guard('admin')->logout();
            $request->session()->forget('admin.last_activity');
            // Rotate the session ID, preserving a separate public member login if any.
            $request->session()->migrate(true);
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'email' => __('The administrator session expired. Please sign in again.'),
            ]);
        }

        $request->session()->put('admin.last_activity', time());

        return $next($request);
    }
}
