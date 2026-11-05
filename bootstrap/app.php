<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureAdminSessionIsFresh;
use App\Http\Middleware\LogAdminActivity;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin', 'admin/*')
            ? route('admin.login')
            : route('login'));
        $middleware->redirectUsersTo(fn (Request $request) => $request->is('admin', 'admin/*')
            ? route('admin.dashboard')
            : route('root'));
        $middleware->web(append: [SecurityHeaders::class]);

        $middleware->alias([
            'locale' => SetLocale::class,
            'admin' => EnsureUserIsAdmin::class,
            'admin.session' => EnsureAdminSessionIsFresh::class,
            'admin.log' => LogAdminActivity::class,
        ]);

        $middleware->prependToPriorityList(
            SubstituteBindings::class,
            SetLocale::class,
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
