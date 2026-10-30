<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogGenreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        $genre = $this->route('blog_genre');
        $rules = [
            'blog_categories' => ['required', 'array', 'min:1'],
            'blog_categories.*' => ['integer', 'distinct', 'exists:blog_categories,id'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'localizations' => ['required', 'array'],
        ];

        foreach (array_keys(config('localization.locales')) as $locale) {
            $existing = $genre?->localizations?->firstWhere('locale', $locale);
            $required = $locale === config('localization.default');

            $rules["localizations.$locale.name"] = [$required ? 'required' : 'nullable', 'string', 'max:120'];
            $rules["localizations.$locale.slug"] = [
                'nullable',
                'string',
                'max:140',
                Rule::unique('blog_genre_localizations', 'slug')
                    ->where(fn ($query) => $query->where('locale', $locale))
                    ->ignore($existing?->id),
            ];
        }

        return $rules;
    }
}
