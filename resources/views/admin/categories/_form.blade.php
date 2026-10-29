@php
    $currentCategory = $category ?? null;
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
        <label class="form-label" for="icon">{{ __('Bootstrap icon') }}</label>
        <input class="form-control" id="icon" name="icon" value="{{ old('icon', $currentCategory?->icon) }}" maxlength="80" placeholder="bi-film">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="sort_order">{{ __('Order') }}</label>
        <input class="form-control" id="sort_order" type="number" min="0" max="9999" name="sort_order" value="{{ old('sort_order', $currentCategory?->sort_order ?? 0) }}">
    </div>
</div>

<hr class="my-4">
<div class="mb-3">
    <h2 class="h5 mb-1">{{ __('Country content and SEO') }}</h2>
    <p class="small text-body-secondary mb-0">{{ __('The default country is required. Other countries are optional. Fill a country to create its version.') }}</p>
</div>

<ul class="nav nav-tabs" role="tablist">
    @foreach ($locales as $locale => $settings)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="category-tab-{{ $locale }}" data-bs-toggle="tab" data-bs-target="#category-panel-{{ $locale }}" type="button" role="tab">
                {{ $settings['country'] ?? strtoupper($settings['region']) }}
                @if ($locale === $defaultLocale)<span class="badge text-bg-danger ms-1">{{ __('Default') }}</span>@endif
            </button>
        </li>
    @endforeach
</ul>

<div class="tab-content border border-top-0 border-secondary-subtle rounded-bottom p-3 p-lg-4">
    @foreach ($locales as $locale => $settings)
        @php
            $localized = $currentCategory?->localizations?->firstWhere('locale', $locale);
        @endphp
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="category-panel-{{ $locale }}" role="tabpanel" aria-labelledby="category-tab-{{ $locale }}">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label" for="name-{{ $locale }}">{{ __('Name') }}</label>
                    <input class="form-control" id="name-{{ $locale }}" data-category-name="{{ $locale }}" name="localizations[{{ $locale }}][name]" value="{{ old("localizations.$locale.name", $localized?->name) }}" maxlength="120" {{ $locale === $defaultLocale ? 'required' : '' }}>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="slug-{{ $locale }}">{{ __('Slug') }}</label>
                    <input class="form-control" id="slug-{{ $locale }}" name="localizations[{{ $locale }}][slug]" value="{{ old("localizations.$locale.slug", $localized?->slug) }}" maxlength="140" placeholder="movies">
                    <div class="form-text">{{ __('Leave blank to generate it from the name.') }}</div>
                </div>
                <div class="col-12">
                    <label class="form-label" for="description-{{ $locale }}">{{ __('Description') }}</label>
                    <textarea class="form-control" id="description-{{ $locale }}" data-category-description="{{ $locale }}" name="localizations[{{ $locale }}][description]" rows="3" maxlength="1000">{{ old("localizations.$locale.description", $localized?->description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label" for="seo-title-{{ $locale }}">{{ __('SEO title') }}</label>
                    <input class="form-control" id="seo-title-{{ $locale }}" data-category-seo-title="{{ $locale }}" name="localizations[{{ $locale }}][seo_title]" value="{{ old("localizations.$locale.seo_title", $localized?->seo_title) }}" maxlength="70">
                </div>
                <div class="col-12">
                    <label class="form-label" for="seo-description-{{ $locale }}">{{ __('SEO description') }}</label>
                    <textarea class="form-control" id="seo-description-{{ $locale }}" data-category-seo-description="{{ $locale }}" name="localizations[{{ $locale }}][seo_description]" rows="2" maxlength="170">{{ old("localizations.$locale.seo_description", $localized?->seo_description) }}</textarea>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-category-name]').forEach((nameInput) => {
        const locale = nameInput.dataset.categoryName;
        const descriptionInput = document.querySelector(`[data-category-description="${locale}"]`);
        const seoTitleInput = document.querySelector(`[data-category-seo-title="${locale}"]`);
        const seoDescriptionInput = document.querySelector(`[data-category-seo-description="${locale}"]`);

        if (!seoTitleInput || !seoDescriptionInput) return;

        let seoTitleAutomatic = seoTitleInput.value.trim() === '';
        let seoDescriptionAutomatic = seoDescriptionInput.value.trim() === '';

        const updateSeo = () => {
            const name = nameInput.value.trim();
            const description = descriptionInput?.value.trim() || '';
            if (seoTitleAutomatic) seoTitleInput.value = name.slice(0, 70);
            if (seoDescriptionAutomatic) seoDescriptionInput.value = (description || (name ? `Explore ${name} on {{ config('site.name') }}.` : '')).slice(0, 170);
        };

        seoTitleInput.addEventListener('input', () => { seoTitleAutomatic = seoTitleInput.value.trim() === ''; });
        seoDescriptionInput.addEventListener('input', () => { seoDescriptionAutomatic = seoDescriptionInput.value.trim() === ''; });
        nameInput.addEventListener('input', updateSeo);
        descriptionInput?.addEventListener('input', updateSeo);
        updateSeo();
    });
});
</script>
