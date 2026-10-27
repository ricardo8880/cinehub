<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home', [
        'locale' => config('localization.default'),
    ]);
})->name('root');

$localePattern = implode('|', array_map(
    static fn (string $locale): string => preg_quote($locale, '/'),
    array_keys(config('localization.locales')),
));

Route::prefix('{locale}')
    ->where(['locale' => $localePattern])
    ->middleware('locale')
    ->group(function (): void {
        Route::get('/', HomeController::class)->name('home');
    });
