<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        $rules = [
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'localizations' => ['required', 'array'],
        ];

        foreach (array_keys(config('localization.locales')) as $locale) {
            $required = $locale === config('localization.default');
            $rules["localizations.$locale.name"] = [$required ? 'required' : 'nullable', 'string', 'max:120'];
        }

        return $rules;
    }
}
