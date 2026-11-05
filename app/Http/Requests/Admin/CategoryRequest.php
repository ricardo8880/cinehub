<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use App\Support\SeoDescription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $localizations = (array) $this->input('localizations', []);

        foreach (array_keys(config('localization.locales')) as $locale) {
            $data = (array) ($localizations[$locale] ?? []);
            $name = trim((string) ($data['name'] ?? ''));
            $description = trim((string) ($data['description'] ?? ''));

            if (! empty($data['slug'])) {
                $data['slug'] = str($data['slug'])->slug()->value();
            }

            if (trim((string) ($data['seo_title'] ?? '')) === '' && $name !== '') {
                $data['seo_title'] = Str::limit($name, 70, '');
            }

            if (trim((string) ($data['seo_description'] ?? '')) === '' && $name !== '') {
                $fallback = $description !== '' ? $description : trans('Explore :category on :site.', ['category' => $name, 'site' => config('site.name')], config("localization.locales.{$locale}.locale"));
                $data['seo_description'] = SeoDescription::summarize($fallback);
            }

            $localizations[$locale] = $data;
        }

        $this->merge([
            'is_active' => true,
            'localizations' => $localizations,
        ]);
    }

    public function rules(): array
    {
        /** @var Category|null $category */
        $category = $this->route('category');
        $rules = [
            'icon' => ['nullable', 'string', 'max:80', 'regex:/^bi-[a-z0-9-]+$/'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
            'localizations' => ['required', 'array'],
        ];

        foreach (array_keys(config('localization.locales')) as $locale) {
            $existing = $category?->localizations?->firstWhere('locale', $locale);
            $isDefault = $locale === config('localization.default');
            $hasVersion = $isDefault || trim((string) $this->input("localizations.$locale.name", '')) !== '';

            $rules["localizations.$locale.name"] = [$hasVersion ? 'required' : 'nullable', 'string', 'max:120'];
            $rules["localizations.$locale.slug"] = [
                'nullable',
                'string',
                'max:140',
                Rule::unique('category_localizations', 'slug')
                    ->where(fn ($query) => $query->where('locale', $locale))
                    ->ignore($existing?->id),
            ];
            $rules["localizations.$locale.description"] = ['nullable', 'string', 'max:1000'];
            $rules["localizations.$locale.seo_title"] = ['nullable', 'string', 'max:70'];
            $rules["localizations.$locale.seo_description"] = ['nullable', 'string', 'max:170'];
        }

        return $rules;
    }
}
