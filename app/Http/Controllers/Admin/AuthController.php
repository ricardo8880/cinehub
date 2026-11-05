<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\TimeBasedOneTimePassword;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const PENDING_SECONDS = 10 * 60;

    public function create(): View
    {
        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $email = strtolower(trim($credentials['email']));
        $key = 'admin-login:'.hash('sha256', $email);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => __('Too many attempts. Try again later.')])->onlyInput('email');
        }

        // Check the password without authenticating the guard: a correct password
        // alone must never establish an administrator session.
        $provider = Auth::guard('admin')->getProvider();
        $user = $provider->retrieveByCredentials(['email' => $email]);
        if (! $user instanceof User || ! $user->is_admin
            || ! $provider->validateCredentials($user, ['password' => $credentials['password']])) {
            $this->logFailedAttempt('admin.password_failed', $request, $email);
            RateLimiter::hit($key, 600);
            if (RateLimiter::attempts($key) >= 5) {
                Log::channel('security')->critical('admin.password_lockout', [
                    'email_hash' => hash('sha256', $email), 'ip' => $request->ip(),
                ]);
            }

            return back()->withErrors(['email' => __('The provided credentials do not match our records.')])->onlyInput('email');
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        // Local development uses email and password only. Other environments
        // keep the existing mandatory two-factor authentication flow.
        if (app()->environment('local')) {
            Auth::guard('admin')->login($user, false);
            $request->session()->regenerate();
            $request->session()->forget('admin.2fa');
            $request->session()->put('admin.last_activity', time());
            Log::channel('security')->notice('admin.login_success', [
                'user_id' => $user->getKey(),
                'ip' => $request->ip(),
                'two_factor' => 'skipped_local',
            ]);

            return redirect()->route('admin.dashboard');
        }

        $request->session()->put('admin.2fa.pending_id', $user->getKey());
        $request->session()->put('admin.2fa.started_at', time());
        $request->session()->forget('admin.2fa.enrollment_secret');

        return redirect()->route('admin.two-factor.form');
    }

    public function twoFactorForm(Request $request): View|RedirectResponse
    {
        $user = $this->pendingAdministrator($request);
        if (! $user) {
            return redirect()->route('admin.login');
        }

        $secret = null;
        if (! $user->admin_two_factor_secret) {
            $cipher = $request->session()->get('admin.2fa.enrollment_secret');
            if (is_string($cipher)) {
                try {
                    $secret = Crypt::decryptString($cipher);
                } catch (DecryptException) {
                    // An invalid or expired setup secret is replaced.
                }
            }
            if (! is_string($secret) || $secret === '') {
                $secret = TimeBasedOneTimePassword::generateSecret();
                $request->session()->put('admin.2fa.enrollment_secret', Crypt::encryptString($secret));
            }
        }

        return view('admin.auth.two-factor', ['setupSecret' => $secret]);
    }

    public function verifyTwoFactor(Request $request): RedirectResponse
    {
        $user = $this->pendingAdministrator($request);
        if (! $user) {
            return redirect()->route('admin.login');
        }

        $data = $request->validate(['code' => ['required', 'regex:/^\d{6}$/D']]);
        $key = 'admin-2fa:'.$user->getKey();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['code' => __('Too many attempts. Try again later.')]);
        }

        $secret = $user->admin_two_factor_secret;
        if (! $secret) {
            $cipher = $request->session()->get('admin.2fa.enrollment_secret');
            try {
                $secret = is_string($cipher) ? Crypt::decryptString($cipher) : null;
            } catch (DecryptException) {
                $secret = null;
            }
        }

        $step = is_string($secret)
            ? TimeBasedOneTimePassword::matchingStep($secret, (string) $data['code'])
            : null;

        $valid = $step !== null;
        if ($valid && $user->admin_two_factor_secret) {
            // A TOTP code cannot be reused, even within its 30-second window.
            $valid = User::query()->whereKey($user->getKey())
                ->whereNotNull('admin_two_factor_secret')
                ->where(function ($query) use ($step): void {
                    $query->whereNull('admin_two_factor_last_step')
                        ->orWhere('admin_two_factor_last_step', '<', $step);
                })
                ->update(['admin_two_factor_last_step' => $step]) === 1;
        } elseif ($valid) {
            // First login: 2FA is mandatory, so enrollment is confirmed only
            // after a working authenticator code has been supplied.
            // Atomically prevent a second pending session from replacing an
            // authenticator that was just enrolled in another session.
            $valid = User::query()->whereKey($user->getKey())
                ->whereNull('admin_two_factor_secret')
                ->update([
                    'admin_two_factor_secret' => Crypt::encryptString($secret),
                    'admin_two_factor_last_step' => $step,
                ]) === 1;
            if ($valid) {
                $user->refresh();
            }
        }

        if (! $valid) {
            RateLimiter::hit($key, 300);
            $this->logFailedAttempt('admin.two_factor_failed', $request, $user->email);
            if (RateLimiter::attempts($key) >= 5) {
                Log::channel('security')->critical('admin.two_factor_lockout', [
                    'user_id' => $user->getKey(), 'ip' => $request->ip(),
                ]);
            }

            return back()->withErrors(['code' => __('Invalid or already used verification code.')]);
        }

        RateLimiter::clear($key);
        Auth::guard('admin')->login($user, false); // No persistent admin remember cookie.
        $request->session()->regenerate();
        $request->session()->forget('admin.2fa');
        $request->session()->put('admin.last_activity', time());
        Log::channel('security')->notice('admin.login_success', [
            'user_id' => $user->getKey(), 'ip' => $request->ip(),
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->forget(['admin.last_activity', 'admin.2fa']);
        $request->session()->migrate(true);
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function pendingAdministrator(Request $request): ?User
    {
        $id = $request->session()->get('admin.2fa.pending_id');
        $startedAt = $request->session()->get('admin.2fa.started_at');
        if (! is_numeric($id) || ! is_int($startedAt)
            || $startedAt > time() || time() - $startedAt > self::PENDING_SECONDS) {
            $request->session()->forget('admin.2fa');
            return null;
        }

        $user = User::query()->find($id);
        if (! $user?->is_admin) {
            $request->session()->forget('admin.2fa');
            return null;
        }

        return $user;
    }

    private function logFailedAttempt(string $event, Request $request, string $email): void
    {
        Log::channel('security')->warning($event, [
            'email_hash' => hash('sha256', strtolower($email)),
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 200),
        ]);
    }
}
