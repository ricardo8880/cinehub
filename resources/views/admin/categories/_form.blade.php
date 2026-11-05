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
    <h2 class="h5 mb-1">{{ __('Language content and SEO') }}</h2>
    <p class="small text-body-secondary mb-0">{{ __('English is required. French and German are optional. Fill in a language to publish its version.') }}</p>
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
                <div class="col-12">
                    <label class="form-label" for="name-{{ $locale }}">{{ __('Name') }}</label>
                    <input class="form-control" id="name-{{ $locale }}" data-category-name="{{ $locale }}" name="localizations[{{ $locale }}][name]" value="{{ old("localizations.$locale.name", $localized?->name) }}" maxlength="120" {{ $locale === $defaultLocale ? 'required' : '' }}>
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
    @php
        $seoExplorePrefixesByLocale = [];
        foreach ($locales as $locale => $config) {
            $seoExplorePrefixesByLocale[$locale] = trans('Explore :category on :site.', ['category' => '__CATEGORY__', 'site' => config('site.name')], $config['locale']);
        }
    @endphp
    const seoExplorePrefixes = @json($seoExplorePrefixesByLocale);
    const seoDescriptionPreview = (source) => {
        const characters = [...String(source || '').replace(/\s+/gu, ' ').trim()];
        if (characters.length <= 160) return characters.join('');
        const prefix = characters.slice(0, 160).join('');
        if (/\s/u.test(characters[160])) return prefix.trim();
        return (prefix.replace(/\s+\S*$/u, '').trim() || prefix);
    };

    document.querySelectorAll('[data-category-name]').forEach((nameInput) => {
        const locale = nameInput.dataset.categoryName;
        const descriptionInput = document.querySelector(`[data-category-description="${locale}"]`);
        const seoTitleInput = document.querySelector(`[data-category-seo-title="${locale}"]`);
        const seoDescriptionInput = document.querySelector(`[data-category-seo-description="${locale}"]`);

        if (!seoTitleInput || !seoDescriptionInput) return;

        let seoTitleAutomatic = seoTitleInput.value.trim() === '';
        const originalDescription = descriptionInput?.value.trim() || '';
        const storedSeo = seoDescriptionInput.value.trim();
        let seoDescriptionAutomatic = storedSeo === ''
            || (storedSeo.length >= 160 && storedSeo.length <= 170
                && originalDescription.length > storedSeo.length && originalDescription.startsWith(storedSeo));

        const updateSeo = () => {
            const name = nameInput.value.trim();
            const description = descriptionInput?.value.trim() || '';
            if (seoTitleAutomatic) seoTitleInput.value = name.slice(0, 70);
            if (seoDescriptionAutomatic) seoDescriptionInput.value = seoDescriptionPreview(description || (name ? (seoExplorePrefixes[locale] || '').replace('__CATEGORY__', name) : ''));
        };

        seoTitleInput.addEventListener('input', () => { seoTitleAutomatic = seoTitleInput.value.trim() === ''; });
        seoDescriptionInput.addEventListener('input', () => { seoDescriptionAutomatic = seoDescriptionInput.value.trim() === ''; });
        nameInput.addEventListener('input', updateSeo);
        descriptionInput?.addEventListener('input', updateSeo);
        updateSeo();
    });
});
</script>
