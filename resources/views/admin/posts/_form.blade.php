@php
    $currentPost = $post ?? null;
    $selectedCategories = collect(old('categories', $currentPost?->categories?->pluck('id')->all() ?? []))->map(fn ($id) => (int) $id)->all();
    $publishedValue = old('published_at', $currentPost?->published_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i'));
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="status">{{ __('Status') }}</label>
        <select class="form-select" id="status" name="status" required>
            @foreach (['draft' => 'Draft', 'published' => 'Published'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $currentPost?->status ?? 'draft') === $value)>{{ __($label) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="published_at">{{ __('Publication date') }}</label>
        <input class="form-control" id="published_at" type="datetime-local" name="published_at" value="{{ $publishedValue }}" max="{{ now()->format('Y-m-d\TH:i') }}">
        <div class="form-text">{{ __('Filled automatically. Change it only if you need to use an earlier publication date.') }}</div>
    </div>
</div>

<hr class="my-4">
<h2 class="h5">{{ __('Categories') }}</h2>
<div class="row g-2">
    @forelse ($categories as $category)
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="form-check border border-secondary-subtle rounded p-3 ps-5 h-100">
                <input class="form-check-input" id="category-{{ $category->id }}" type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, $selectedCategories, true))>
                <label class="form-check-label" for="category-{{ $category->id }}">
                    <i class="bi {{ $category->icon ?: 'bi-folder' }} me-1"></i>{{ $category->localization($defaultLocale)?->name ?? $category->name }}
                    @unless ($category->is_active)<span class="badge text-bg-secondary ms-1">{{ __('Inactive') }}</span>@endunless
                </label>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-warning mb-0">{{ __('Create at least one category before saving a post.') }}</div></div>
    @endforelse
</div>

<hr class="my-4">
<div class="d-flex align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h2 class="h5 mb-1">{{ __('Country content and SEO') }}</h2>
        <p class="small text-body-secondary mb-0">{{ __('The default country is required. Other countries are optional. Fill a country to create its version.') }}</p>
    </div>
</div>

<ul class="nav nav-tabs" role="tablist">
    @foreach ($locales as $locale => $settings)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $locale }}" data-bs-toggle="tab" data-bs-target="#panel-{{ $locale }}" type="button" role="tab">
                {{ $settings['country'] ?? strtoupper($settings['region']) }}
                @if ($locale === $defaultLocale)<span class="badge text-bg-danger ms-1">{{ __('Default') }}</span>@endif
            </button>
        </li>
    @endforeach
</ul>

<div class="tab-content border border-top-0 border-secondary-subtle rounded-bottom p-3 p-lg-4">
    @foreach ($locales as $locale => $settings)
        @php
            $localization = $currentPost?->localizations?->firstWhere('locale', $locale);
        @endphp
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="panel-{{ $locale }}" role="tabpanel" aria-labelledby="tab-{{ $locale }}">
            <div class="form-check form-switch mb-3">
                <input type="hidden" name="localizations[{{ $locale }}][is_featured]" value="0">
                <input class="form-check-input" id="featured-{{ $locale }}" type="checkbox" name="localizations[{{ $locale }}][is_featured]" value="1" @checked(old("localizations.$locale.is_featured", $localization?->is_featured ?? false))>
                <label class="form-check-label" for="featured-{{ $locale }}">{{ __('Featured in this country') }}</label>
            </div>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label" for="title-{{ $locale }}">{{ __('Title') }}</label>
                    <input class="form-control" id="title-{{ $locale }}" data-post-title="{{ $locale }}" name="localizations[{{ $locale }}][title]" value="{{ old("localizations.$locale.title", $localization?->title) }}" maxlength="180" {{ $locale === $defaultLocale ? 'required' : '' }}>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="slug-{{ $locale }}">{{ __('Slug') }}</label>
                    <input class="form-control" id="slug-{{ $locale }}" name="localizations[{{ $locale }}][slug]" value="{{ old("localizations.$locale.slug", $localization?->slug) }}" maxlength="200" placeholder="post-title">
                </div>
                <div class="col-12">
                    <label class="form-label" for="excerpt-{{ $locale }}">{{ __('Excerpt') }}</label>
                    <textarea class="form-control" id="excerpt-{{ $locale }}" data-post-excerpt="{{ $locale }}" name="localizations[{{ $locale }}][excerpt]" rows="2" maxlength="500">{{ old("localizations.$locale.excerpt", $localization?->excerpt) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label" for="synopsis-{{ $locale }}">{{ __('Synopsis') }}</label>
                    <textarea class="form-control" id="synopsis-{{ $locale }}" data-post-synopsis="{{ $locale }}" name="localizations[{{ $locale }}][synopsis]" rows="5">{{ old("localizations.$locale.synopsis", $localization?->synopsis) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label" for="review-{{ $locale }}">{{ __('Review') }}</label>
                    <textarea class="form-control" id="review-{{ $locale }}" name="localizations[{{ $locale }}][review]" rows="8">{{ old("localizations.$locale.review", $localization?->review) }}</textarea>
                </div>
            </div>

            @php
                $countryMedia = $currentPost?->media?->where('locale', $locale)->sortByDesc('is_primary')->first();
                $savedMediaType = match ($countryMedia?->type) {
                    'image' => 'image',
                    'trailer' => $countryMedia?->provider === 'youtube' ? 'youtube' : '',
                    default => '',
                };
                $selectedMediaType = old("media.$locale.type", $savedMediaType);
                $savedYoutubeUrl = $countryMedia?->provider === 'youtube'
                    ? ($countryMedia->source_url ?: ($countryMedia->provider_id ? 'https://www.youtube.com/watch?v='.$countryMedia->provider_id : ''))
                    : '';
            @endphp
            <div class="card border-secondary-subtle mt-4">
                <div class="card-header"><i class="bi bi-play-btn me-1"></i>{{ __('Media') }}</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="media-type-{{ $locale }}">{{ __('Media type') }}</label>
                            <select class="form-select" id="media-type-{{ $locale }}" name="media[{{ $locale }}][type]" data-country-media-type="{{ $locale }}">
                                <option value="" @selected($selectedMediaType === '')>{{ __('No media') }}</option>
                                <option value="image" @selected($selectedMediaType === 'image')>{{ __('Image') }}</option>
                                <option value="youtube" @selected($selectedMediaType === 'youtube')>{{ __('YouTube video') }}</option>
                            </select>
                        </div>

                        <div class="col-12 {{ $selectedMediaType === 'image' ? '' : 'd-none' }}" data-country-image-fields="{{ $locale }}">
                            <label class="form-label" for="media-image-{{ $locale }}">{{ __('Image') }}</label>
                            <input class="form-control" id="media-image-{{ $locale }}" type="file" name="media[{{ $locale }}][image]" accept="image/jpeg,image/png,image/webp,image/gif">
                            <div class="form-text">{{ __('JPEG, PNG, WebP or GIF. Maximum 5 MB.') }}</div>
                            @if ($countryMedia?->type === 'image' && $countryMedia?->thumbnail_path)
                                <img src="{{ asset('storage/'.$countryMedia->thumbnail_path) }}" class="img-thumbnail img-fluid mt-2 w-50" alt="">
                            @endif
                            <label class="form-label mt-3" for="media-alt-{{ $locale }}">{{ __('Alternative text') }}</label>
                            <input class="form-control" id="media-alt-{{ $locale }}" name="media[{{ $locale }}][alt_text]" maxlength="180" value="{{ old("media.$locale.alt_text", $countryMedia?->type === 'image' ? $countryMedia?->alt_text : '') }}">
                        </div>

                        <div class="col-12 {{ $selectedMediaType === 'youtube' ? '' : 'd-none' }}" data-country-youtube-fields="{{ $locale }}">
                            <label class="form-label" for="media-youtube-{{ $locale }}">{{ __('YouTube URL') }}</label>
                            <input class="form-control" id="media-youtube-{{ $locale }}" type="url" name="media[{{ $locale }}][youtube_url]" value="{{ old("media.$locale.youtube_url", $savedYoutubeUrl) }}" placeholder="https://www.youtube.com/watch?v=...">
                            <div class="form-text">{{ __('Paste only the YouTube URL. The system extracts the video ID and creates the embed automatically.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-secondary-subtle mt-4">
                <div class="card-header"><i class="bi bi-google me-1"></i>{{ __('SEO') }}</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="seo-title-{{ $locale }}">{{ __('SEO title') }}</label>
                            <input class="form-control" id="seo-title-{{ $locale }}" data-post-seo-title="{{ $locale }}" name="localizations[{{ $locale }}][seo_title]" value="{{ old("localizations.$locale.seo_title", $localization?->seo_title) }}" maxlength="70">
                            <div class="form-text">{{ __('Filled automatically from the title. You can change it.') }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="seo-description-{{ $locale }}">{{ __('SEO description') }}</label>
                            <textarea class="form-control" id="seo-description-{{ $locale }}" data-post-seo-description="{{ $locale }}" name="localizations[{{ $locale }}][seo_description]" rows="2" maxlength="170">{{ old("localizations.$locale.seo_description", $localization?->seo_description) }}</textarea>
                            <div class="form-text">{{ __('Filled automatically from the excerpt or synopsis. You can change it.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-country-media-type]').forEach((typeSelect) => {
        const locale = typeSelect.dataset.countryMediaType;
        const imageFields = document.querySelector(`[data-country-image-fields="${locale}"]`);
        const youtubeFields = document.querySelector(`[data-country-youtube-fields="${locale}"]`);

        const updateMediaFields = () => {
            imageFields?.classList.toggle('d-none', typeSelect.value !== 'image');
            youtubeFields?.classList.toggle('d-none', typeSelect.value !== 'youtube');
        };

        typeSelect.addEventListener('change', updateMediaFields);
        updateMediaFields();
    });

    document.querySelectorAll('[data-post-title]').forEach((titleInput) => {
        const locale = titleInput.dataset.postTitle;
        const excerptInput = document.querySelector(`[data-post-excerpt="${locale}"]`);
        const synopsisInput = document.querySelector(`[data-post-synopsis="${locale}"]`);
        const seoTitleInput = document.querySelector(`[data-post-seo-title="${locale}"]`);
        const seoDescriptionInput = document.querySelector(`[data-post-seo-description="${locale}"]`);

        if (!seoTitleInput || !seoDescriptionInput) {
            return;
        }

        let seoTitleAutomatic = seoTitleInput.value.trim() === '';
        let seoDescriptionAutomatic = seoDescriptionInput.value.trim() === '';

        const updateSeoTitle = () => {
            if (seoTitleAutomatic) {
                seoTitleInput.value = titleInput.value.trim().slice(0, 70);
            }
        };

        const updateSeoDescription = () => {
            if (!seoDescriptionAutomatic) {
                return;
            }

            const excerpt = excerptInput?.value.trim() || '';
            const synopsis = synopsisInput?.value.trim() || '';
            const title = titleInput.value.trim();
            const fallback = title ? `Read ${title} on {{ config('site.name') }}.` : '';
            seoDescriptionInput.value = (excerpt || synopsis || fallback).slice(0, 170);
        };

        seoTitleInput.addEventListener('input', () => {
            seoTitleAutomatic = seoTitleInput.value.trim() === '';
        });

        seoDescriptionInput.addEventListener('input', () => {
            seoDescriptionAutomatic = seoDescriptionInput.value.trim() === '';
        });

        titleInput.addEventListener('input', () => {
            updateSeoTitle();
            updateSeoDescription();
        });
        excerptInput?.addEventListener('input', updateSeoDescription);
        synopsisInput?.addEventListener('input', updateSeoDescription);

        updateSeoTitle();
        updateSeoDescription();
    });
});
</script>

