@php($currentMedia = $medium ?? null)

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
    <div class="col-md-4">
        <label class="form-label" for="type">{{ __('Type') }}</label>
        <select class="form-select" id="type" name="type" required>
            <option value="image" @selected(old('type', $currentMedia?->type ?? 'image') === 'image')>{{ __('Image') }}</option>
            <option value="trailer" @selected(old('type', $currentMedia?->type) === 'trailer')>{{ __('Trailer / video') }}</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label" for="locale">{{ __('Country') }}</label>
        <select class="form-select" id="locale" name="locale">
            <option value="">{{ __('All countries') }}</option>
            @foreach ($locales as $locale => $settings)
                <option value="{{ $locale }}" @selected(old('locale', $currentMedia?->locale) === $locale)>{{ $settings['country'] ?? strtoupper($settings['region']) }} · {{ $locale }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label" for="sort_order">{{ __('Order') }}</label>
        <input class="form-control" id="sort_order" type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $currentMedia?->sort_order ?? 0) }}">
    </div>

    <div class="col-12">
        <label class="form-label" for="image">{{ __('Image / thumbnail') }}</label>
        <input class="form-control" id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
        <div class="form-text">{{ __('Maximum 5 MB. Use optimized images whenever possible.') }}</div>
        @if ($currentMedia?->thumbnail_path)
            <img src="{{ asset('storage/'.$currentMedia->thumbnail_path) }}" class="img-thumbnail img-fluid mt-2 w-50" alt="">
        @endif
    </div>
    <div class="col-12">
        <label class="form-label" for="alt_text">{{ __('Alternative text') }}</label>
        <input class="form-control" id="alt_text" name="alt_text" maxlength="180" value="{{ old('alt_text', $currentMedia?->alt_text) }}">
        <div class="form-text">{{ __('Describe the image for accessibility and SEO.') }}</div>
    </div>
    <div class="col-12">
        <label class="form-label" for="caption">{{ __('Caption') }}</label>
        <textarea class="form-control" id="caption" name="caption" rows="2" maxlength="500">{{ old('caption', $currentMedia?->caption) }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="provider">{{ __('Video provider') }}</label>
        <input class="form-control" id="provider" name="provider" maxlength="60" value="{{ old('provider', $currentMedia?->provider) }}" placeholder="youtube">
    </div>
    <div class="col-md-4">
        <label class="form-label" for="provider_id">{{ __('Provider ID') }}</label>
        <input class="form-control" id="provider_id" name="provider_id" maxlength="180" value="{{ old('provider_id', $currentMedia?->provider_id) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label" for="duration_seconds">{{ __('Duration (seconds)') }}</label>
        <input class="form-control" id="duration_seconds" type="number" name="duration_seconds" min="1" max="86400" value="{{ old('duration_seconds', $currentMedia?->duration_seconds) }}">
    </div>
    <div class="col-12">
        <label class="form-label" for="embed_url">{{ __('Embed URL') }}</label>
        <input class="form-control" id="embed_url" type="url" name="embed_url" value="{{ old('embed_url', $currentMedia?->embed_url) }}" placeholder="https://www.youtube-nocookie.com/embed/...">
    </div>
    <div class="col-12">
        <label class="form-label" for="source_url">{{ __('Source / rights URL') }}</label>
        <input class="form-control" id="source_url" type="url" name="source_url" value="{{ old('source_url', $currentMedia?->source_url) }}">
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input type="hidden" name="is_primary" value="0">
            <input class="form-check-input" id="is_primary" type="checkbox" name="is_primary" value="1" @checked(old('is_primary', $currentMedia?->is_primary ?? false))>
            <label class="form-check-label" for="is_primary">{{ __('Primary media for this type') }}</label>
        </div>
    </div>
</div>
