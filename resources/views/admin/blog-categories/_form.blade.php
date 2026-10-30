@php($currentCategory = $category ?? null)

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label" for="sort_order">{{ __('Order') }}</label>
        <input class="form-control" id="sort_order" type="number" min="0" max="9999" name="sort_order" value="{{ old('sort_order', $currentCategory?->sort_order ?? 0) }}">
    </div>
    <div class="col-md-8 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" id="is_active" type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $currentCategory?->is_active ?? true))>
            <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
        </div>
    </div>
</div>

<hr class="my-4">
<div class="mb-3">
    <h2 class="h5 mb-1">{{ __('Names by country') }}</h2>
    <p class="small text-body-secondary mb-0">{{ __('The default country is required. Other countries are optional. The Blog category remains a single category shared by all country versions.') }}</p>
</div>

<ul class="nav nav-tabs" role="tablist">
    @foreach ($locales as $locale => $settings)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="blog-category-tab-{{ $locale }}" data-bs-toggle="tab" data-bs-target="#blog-category-panel-{{ $locale }}" type="button" role="tab">
                {{ $settings['country'] ?? strtoupper($settings['region']) }}
                @if ($locale === $defaultLocale)<span class="badge text-bg-danger ms-1">{{ __('Default') }}</span>@endif
            </button>
        </li>
    @endforeach
</ul>

<div class="tab-content border border-top-0 border-secondary-subtle rounded-bottom p-3 p-lg-4">
    @foreach ($locales as $locale => $settings)
        @php($localized = $currentCategory?->localizations?->firstWhere('locale', $locale))
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="blog-category-panel-{{ $locale }}" role="tabpanel">
            <label class="form-label" for="blog-category-name-{{ $locale }}">{{ __('Name') }}</label>
            <input class="form-control" id="blog-category-name-{{ $locale }}" name="localizations[{{ $locale }}][name]" value="{{ old("localizations.$locale.name", $localized?->name) }}" maxlength="120" {{ $locale === $defaultLocale ? 'required' : '' }} placeholder="Editorial">
        </div>
    @endforeach
</div>
