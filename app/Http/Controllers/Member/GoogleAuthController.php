<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request, string $locale): RedirectResponse
    {
        abort_unless(config('cinehub_auth.google_client_id') && config('cinehub_auth.google_client_secret'), 503);
        $state = Str::random(48);
        $request->session()->put('google_oauth_state', $state);
        $request->session()->put('google_oauth_locale', $locale);
        $query = http_build_query([
            'client_id' => config('cinehub_auth.google_client_id'),
            'redirect_uri' => route('member.google.callback', ['locale' => $locale]),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
        ]);
        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.$query);
    }

    public function callback(Request $request, string $locale): RedirectResponse
    {
        $expected = $request->session()->pull('google_oauth_state');
        $expectedLocale = $request->session()->pull('google_oauth_locale');
        abort_unless(is_string($expected) && is_string($request->query('state')) && hash_equals($expected, $request->query('state')) && $locale === $expectedLocale, 403);
        if (! $request->filled('code')) return redirect()->route('member.login', ['locale' => $locale])->withErrors(['email' => __('Google sign-in was cancelled.')]);

        $tokenResponse = Http::asForm()->timeout(10)->post('https://oauth2.googleapis.com/token', [
            'code' => $request->query('code'),
            'client_id' => config('cinehub_auth.google_client_id'),
            'client_secret' => config('cinehub_auth.google_client_secret'),
            'redirect_uri' => route('member.google.callback', ['locale' => $locale]),
            'grant_type' => 'authorization_code',
        ]);
        abort_unless($tokenResponse->successful() && is_string($tokenResponse->json('access_token')), 403);

        $profile = Http::withToken($tokenResponse->json('access_token'))
            ->acceptJson()->timeout(10)->get('https://openidconnect.googleapis.com/v1/userinfo');
        abort_unless($profile->successful() && $profile->json('email_verified') === true, 403);
        $sub = (string) $profile->json('sub');
        $email = strtolower((string) $profile->json('email'));
        abort_unless($sub !== '' && filter_var($email, FILTER_VALIDATE_EMAIL), 403);

        $user = User::query()->where('google_id', $sub)->first();
        if (! $user) {
            $user = User::query()->where('email', $email)->first();
            // Never silently link an existing administrator to a public OAuth identity.
            if ($user?->is_admin) throw ValidationException::withMessages(['email' => __('Use the administrator login for this account.')]);
            if ($user && $user->google_id && $user->google_id !== $sub) abort(403);
            if (! $user) {
                $user = User::create([
                    'name' => Str::limit((string) $profile->json('name', 'Member'), 80, ''),
                    'email' => $email,
                    'password' => Str::random(60),
                    'is_admin' => false,
                ]);
            }
            $user->google_id = $sub;
            $user->markEmailAsVerified();
            $user->save();
        }
        // Public Google login must never become an alternative admin authentication path.
        if ($user->is_admin) throw ValidationException::withMessages(['email' => __('Use the administrator login for this account.')]);
        Auth::guard('web')->setRememberDuration(60 * 24 * 30);
        Auth::login($user, true);
        $request->session()->regenerate();
        return redirect()->intended(route('member.for-you', ['locale' => $locale]));
    }
}
