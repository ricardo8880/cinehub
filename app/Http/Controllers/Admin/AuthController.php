<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        // A persistent, revocable authentication cookie is issued only when
        // "Remember me" is checked. It expires after 30 days, or on logout.
        $guard = Auth::guard('web');
        $guard->setRememberDuration(60 * 24 * 30);

        if (! $guard->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => __('The provided credentials do not match our records.')])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! $guard->user()?->is_admin) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => __('This account does not have administrative access.')])
                ->onlyInput('email');
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
