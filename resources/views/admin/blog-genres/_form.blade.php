@php
    $currentGenre = $genre ?? null;
    $selectedCategories = collect(old('blog_categories', $currentGenre?->categories?->pluck('id')->all() ?? []))->map(fn ($id) => (int) $id)->all();
@endphp

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
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
<h2 class="h5 mb-1">{{ __('Available in Blog categories') }}</h2>
<p class="small text-body-secondary">{{ __('These categories are internal. Visitors only see the Blog genre.') }}</p>
<div class="row g-2 mb-4">
    @forelse ($categories as $category)
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="form-check border border-secondary-subtle rounded p-3 ps-5 h-100">
                <input class="form-check-input" id="blog-genre-category-{{ $category->id }}" type="checkbox" name="blog_categories[]" value="{{ $category->id }}" @checked(in_array($category->id, $selectedCategories, true))>
                <label class="form-check-label" for="blog-genre-category-{{ $category->id }}">{{ $category->name }}</label>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-warning mb-0">{{ __('Create at least one Blog category before creating a Blog genre.') }}</div></div>
    @endforelse
</div>

<h2 class="h5 mb-3">{{ __('Names by country') }}</h2>
<ul class="nav nav-tabs" role="tablist">
    @foreach ($locales as $locale => $settings)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="blog-genre-tab-{{ $locale }}" data-bs-toggle="tab" data-bs-target="#blog-genre-panel-{{ $locale }}" type="button" role="tab">
                {{ $settings['country'] ?? strtoupper($settings['region']) }}
                @if ($locale === $defaultLocale)<span class="badge text-bg-danger ms-1">{{ __('Default') }}</span>@endif
            </button>
        </li>
    @endforeach
</ul>
<div class="tab-content border border-top-0 border-secondary-subtle rounded-bottom p-3 p-lg-4">
    @foreach ($locales as $locale => $settings)
        @php($localized = $currentGenre?->localizations?->firstWhere('locale', $locale))
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="blog-genre-panel-{{ $locale }}" role="tabpanel">
            <label class="form-label" for="blog-genre-name-{{ $locale }}">{{ __('Name') }}</label>
            <input class="form-control" id="blog-genre-name-{{ $locale }}" name="localizations[{{ $locale }}][name]" value="{{ old("localizations.$locale.name", $localized?->name) }}" maxlength="120" {{ $locale === $defaultLocale ? 'required' : '' }} placeholder="Nostalgia">
            <div class="form-text">{{ __('Slug is generated automatically from the name.') }}</div>
        </div>
    @endforeach
</div>
