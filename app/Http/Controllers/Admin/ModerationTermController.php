<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModerationTerm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ModerationTermController extends Controller
{
    public function index(): View
    {
        return view('admin.moderation.index', ['terms' => ModerationTerm::query()->latest()->paginate(30)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        ModerationTerm::create($data);
        $this->clearCache();
        return back()->with('success', __('Moderation rule added.'));
    }

    public function update(Request $request, ModerationTerm $moderationTerm): RedirectResponse
    {
        $moderationTerm->update($this->validated($request, $moderationTerm));
        $this->clearCache();
        return back()->with('success', __('Moderation rule updated.'));
    }

    public function destroy(ModerationTerm $moderationTerm): RedirectResponse
    {
        $moderationTerm->delete();
        $this->clearCache();
        return back()->with('success', __('Moderation rule removed.'));
    }

    private function validated(Request $request, ?ModerationTerm $rule = null): array
    {
        $localeOptions = array_merge(['*'], array_keys(config('localization.locales')));
        return $request->validate([
            'phrase' => ['required', 'string', 'min:3', 'max:150', Rule::unique('moderation_terms', 'phrase')->where('locale', $request->input('locale', '*'))->ignore($rule?->id)],
            'locale' => ['required', Rule::in($localeOptions)],
            'classification' => ['required', Rule::in(['blocked', 'review'])],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    private function clearCache(): void
    {
        foreach (array_keys(config('localization.locales')) as $locale) {
            Cache::forget('cinehub:moderation:'.$locale);
        }
    }
}
