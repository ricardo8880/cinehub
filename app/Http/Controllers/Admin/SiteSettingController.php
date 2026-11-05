<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    private const BOOK_FIELDS = ['title', 'synopsis', 'cover_url', 'sample_url', 'purchase_url'];

    private static function keys(): array
    {
        $keys = ['contact_email', 'saas_url', 'donation_url'];
        foreach (array_keys(config('localization.locales')) as $locale) {
            foreach (self::BOOK_FIELDS as $field) {
                $keys[] = SiteSetting::bookKey($field, $locale);
            }
        }

        return array_values(array_unique($keys));
    }

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => SiteSetting::whereIn('key', self::keys())->pluck('value', 'key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [
            'contact_email' => ['nullable', 'email', 'max:255'],
            'saas_url' => ['nullable', 'url', 'max:2000'],
            'donation_url' => ['nullable', 'url', 'max:2000'],
        ];
        foreach (array_keys(config('localization.locales')) as $locale) {
            foreach (self::BOOK_FIELDS as $field) {
                $rules[SiteSetting::bookKey($field, $locale)] = match ($field) {
                    'title' => ['nullable', 'string', 'max:180'],
                    'synopsis' => ['nullable', 'string', 'max:5000'],
                    default => ['nullable', 'url', 'max:2000'],
                };
            }
        }

        $data = $request->validate($rules);

        foreach (self::keys() as $key) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $data[$key] ?? null]);
        }

        return back()->with('success', __('Settings saved.'));
    }
}
