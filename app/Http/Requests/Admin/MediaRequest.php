<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_primary' => $this->boolean('is_primary'),
            'locale' => $this->input('locale') ?: null,
        ]);
    }

    public function rules(): array
    {
        $supportedLocales = array_keys(config('localization.locales'));

        return [
            'type' => ['required', Rule::in(['image', 'trailer'])],
            'locale' => ['nullable', Rule::in($supportedLocales)],
            'provider' => ['nullable', Rule::in(['youtube', 'vimeo'])],
            'provider_id' => ['nullable', 'string', 'max:180'],
            'image' => ['nullable', 'image', 'max:5120'],
            'alt_text' => ['required_if:type,image', 'nullable', 'string', 'max:180'],
            'caption' => ['nullable', 'string', 'max:500'],
            'source_url' => ['nullable', 'url', 'max:2000'],
            'duration_seconds' => ['nullable', 'integer', 'min:1', 'max:86400'],
            'is_primary' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }
}
