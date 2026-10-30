@php
    $currentGenre = $genre ?? null;
    $selectedCategories = collect(old('categories', $currentGenre?->categories?->pluck('id')->all() ?? []))->map(fn ($id) => (int) $id)->all();
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label" for="sort_order">{{ __('Order') }}</label>
        <input class="form-control" id="sort_order" type="number" min="0" max="9999" name="sort_order" value="{{ old('sort_order', $currentGenre?->sort_order ?? 0) }}">
    </div>
    <div class="col-md-8 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" id="is_active" type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $currentGenre?->is_active ?? true))>
            <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
        </div>
    </div>
</div>

<hr class="my-4">
<h2 class="h5 mb-1">{{ __('Available in categories') }}</h2>
<p class="text-body-secondary small">{{ __('Choose the category where this genre can be used.') }}</p>
<div class="row g-2 mb-4">
    @forelse ($categories as $category)
        @php
            $activeLocalizations = $category->localizations
                ->where('is_active', true)
                ->sortBy(fn ($localization) => array_search($localization->locale, array_keys($locales), true));
        @endphp
        <div class="col-12 col-md-6 col-xl-4">
            <div class="form-check border border-secondary-subtle rounded p-3 ps-5 h-100">
                <input class="form-check-input" id="genre-category-{{ $category->id }}" type="radio" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, $selectedCategories, true))>
                <label class="form-check-label w-100" for="genre-category-{{ $category->id }}">
                    <div class="fw-semibold mb-2">
                        <i class="bi {{ $category->icon ?: 'bi-folder' }} me-1"></i>{{ __('Category') }}
                    </div>
                    <div class="d-flex flex-column gap-1">
                        @forelse ($activeLocalizations as $localization)
                            @php
                                $settings = $locales[$localization->locale] ?? [];
                                $country = $settings['country'] ?? strtoupper($settings['region'] ?? $localization->locale);
                            @endphp
                            <div>
                                <span class="badge text-bg-secondary me-1">{{ $country }}</span>
                                <span>{{ $localization->name }}</span>
                            </div>
                        @empty
                            <div>
                                <span class="badge text-bg-secondary me-1">{{ strtoupper($defaultLocale) }}</span>
                                <span>{{ $category->name }}</span>
                            </div>
                        @endforelse
                    </div>
                </label>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-warning mb-0">{{ __('Create at least one category before creating a genre.') }}</div></div>
    @endforelse
</div>

<h2 class="h5 mb-3">{{ __('Names by country') }}</h2>
<ul class="nav nav-tabs" role="tablist">
    @foreach ($locales as $locale => $settings)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="genre-tab-{{ $locale }}" data-bs-toggle="tab" data-bs-target="#genre-panel-{{ $locale }}" type="button" role="tab">
                {{ $settings['country'] ?? strtoupper($settings['region']) }}
                @if ($locale === $defaultLocale)<span class="badge text-bg-danger ms-1">{{ __('Default') }}</span>@endif
            </button>
        </li>
    @endforeach
</ul>
<div class="tab-content border border-top-0 border-secondary-subtle rounded-bottom p-3 p-lg-4">
    @foreach ($locales as $locale => $settings)
        @php($localized = $currentGenre?->localizations?->firstWhere('locale', $locale))
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="genre-panel-{{ $locale }}" role="tabpanel">
            <label class="form-label" for="genre-name-{{ $locale }}">{{ __('Name') }}</label>
            <input class="form-control" id="genre-name-{{ $locale }}" name="localizations[{{ $locale }}][name]" value="{{ old("localizations.$locale.name", $localized?->name) }}" maxlength="120" {{ $locale === $defaultLocale ? 'required' : '' }} placeholder="RPG">
            <div class="form-text">{{ __('Slug is generated automatically from the name.') }}</div>
        </div>
    @endforeach
</div>
