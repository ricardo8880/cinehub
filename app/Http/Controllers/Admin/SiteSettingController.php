<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    private const KEYS = [
        'contact_email', 'saas_url', 'donation_url', 'book_title', 'book_synopsis',
        'book_cover_url', 'book_sample_url', 'book_purchase_url',
    ];

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => SiteSetting::whereIn('key', self::KEYS)->pluck('value', 'key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'contact_email' => ['nullable', 'email', 'max:255'],
            'saas_url' => ['nullable', 'url', 'max:2000'],
            'donation_url' => ['nullable', 'url', 'max:2000'],
            'book_title' => ['nullable', 'string', 'max:180'],
            'book_synopsis' => ['nullable', 'string', 'max:5000'],
            'book_cover_url' => ['nullable', 'url', 'max:2000'],
            'book_sample_url' => ['nullable', 'url', 'max:2000'],
            'book_purchase_url' => ['nullable', 'url', 'max:2000'],
        ]);

        foreach (self::KEYS as $key) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $data[$key] ?? null]);
        }

        return back()->with('success', __('Settings saved.'));
    }
}
