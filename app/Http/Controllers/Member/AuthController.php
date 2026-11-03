<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(Request $request, string $locale): View|RedirectResponse
    {
        if ($request->user()) return redirect()->route('member.for-you', ['locale' => $locale]);
        return view('member.login');
    }

    public function register(Request $request, string $locale): View|RedirectResponse
    {
        if ($request->user()) return redirect()->route('member.for-you', ['locale' => $locale]);
        return view('member.register');
    }

    public function create(Request $request, string $locale): RedirectResponse
    {
        abort_if($request->user(), 403);
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', PasswordRule::min(10)],
        ]);

        $user = User::create([
            'name' => trim($data['name']),
            'email' => strtolower(trim($data['email'])),
            'password' => $data['password'],
            'is_admin' => false,
        ]);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member.for-you', ['locale' => $locale]);
    }

    public function authenticate(Request $request, string $locale): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['email' => strtolower(trim($data['email'])), 'password' => $data['password']], $request->boolean('remember'))) {
            return back()->withErrors(['email' => __('Invalid login credentials.')])->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->intended(route('member.for-you', ['locale' => $locale]));
    }

    public function logout(Request $request, string $locale): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home', ['locale' => $locale]);
    }

    public function forgot(): View { return view('member.forgot'); }

    public function sendReset(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        Password::sendResetLink($request->only('email'));
        return back()->with('status', __('If this account exists, a reset link has been sent.'));
    }

    public function resetForm(Request $request, string $locale, string $token): View
    {
        return view('member.reset', ['token' => $token, 'email' => $request->query('email', '')]);
    }

    public function reset(Request $request, string $locale): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(10)],
        ]);
        $status = Password::reset($data, function (User $user, string $password): void {
            $user->forceFill(['password' => $password, 'remember_token' => \Illuminate\Support\Str::random(60)])->save();
        });
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('member.login', ['locale' => $locale])->with('status', __('Password updated.'))
            : back()->withErrors(['email' => __($status)]);
    }
}
