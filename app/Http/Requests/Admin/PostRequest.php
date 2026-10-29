<?php

namespace App\Http\Requests\Admin;

use App\Models\Post;
use App\Support\RichTextHtml;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
            if (! empty($data['slug'])) {
                $data['slug'] = str($data['slug'])->slug()->value();
            }

            foreach (['excerpt', 'synopsis', 'review'] as $richTextField) {
                if (array_key_exists($richTextField, $data)) {
                    $data[$richTextField] = RichTextHtml::normalize($data[$richTextField]);
                }
            }

            $title = trim((string) ($data['title'] ?? ''));
            $seoTitle = trim((string) ($data['seo_title'] ?? ''));
            $seoDescription = trim((string) ($data['seo_description'] ?? ''));

            if ($seoTitle === '' && $title !== '') {
                $data['seo_title'] = Str::limit($title, 70, '');
            }

            if ($seoDescription === '' && $title !== '') {
                $excerpt = RichTextHtml::plainText($data['excerpt'] ?? null);
                $synopsis = RichTextHtml::plainText($data['synopsis'] ?? null);
                $fallback = $excerpt !== ''
                    ? $excerpt
                    : ($synopsis !== '' ? $synopsis : 'Read '.$title.' on '.config('site.name').'.');

                $data['seo_description'] = Str::limit($fallback, 170, '');
            }

            $localizations[$locale] = $data;
        }

        $this->merge([
            'localizations' => $localizations,
        ]);
    }

    public function rules(): array
    {
        /** @var Post|null $post */
        $post = $this->route('post');
        $rules = [
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date', 'before_or_equal:now'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'localizations' => ['required', 'array'],
            'media' => ['nullable', 'array'],
        ];

        foreach (array_keys(config('localization.locales')) as $locale) {
            $existingLocalization = $post?->localizations?->firstWhere('locale', $locale);
            $hasCountryVersion = $locale === config('localization.default')
                || trim((string) $this->input("localizations.$locale.title", '')) !== '';

            $rules["localizations.$locale.title"] = [$hasCountryVersion ? 'required' : 'nullable', 'string', 'max:180'];
            $rules["localizations.$locale.is_featured"] = ['nullable', 'boolean'];
            $rules["localizations.$locale.slug"] = [
                'nullable',
                'string',
                'max:200',
                Rule::unique('post_localizations', 'slug')
                    ->where(fn ($query) => $query->where('locale', $locale))
                    ->ignore($existingLocalization?->id),
            ];
            $rules["localizations.$locale.excerpt"] = [
                'nullable',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (mb_strlen(RichTextHtml::plainText((string) $value)) > 500) {
                        $fail(__('The excerpt may not be greater than 500 characters.'));
                    }
                },
            ];
            $rules["localizations.$locale.synopsis"] = ['nullable', 'string'];
            $rules["localizations.$locale.review"] = ['nullable', 'string'];
            $rules["localizations.$locale.spoilers"] = ['nullable', 'string'];
            $rules["localizations.$locale.seo_title"] = ['nullable', 'string', 'max:70'];
            $rules["localizations.$locale.seo_description"] = ['nullable', 'string', 'max:170'];
            $rules["media.$locale.type"] = ['nullable', Rule::in(['image', 'youtube'])];
            $rules["media.$locale.image"] = ['nullable', 'image', 'max:5120'];
            $rules["media.$locale.youtube_url"] = ['nullable', 'url', 'max:2000'];
            $rules["media.$locale.alt_text"] = ['nullable', 'string', 'max:180'];
        }

        return $rules;
    }
}
