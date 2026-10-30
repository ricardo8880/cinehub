@php($currentCategory = $category ?? null)

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label" for="name">{{ __('Name') }}</label>
        <input class="form-control" id="name" name="name" value="{{ old('name', $currentCategory?->name) }}" maxlength="120" required placeholder="Editorial">
        <div class="form-text">{{ __('Internal organization only. Blog visitors will not see this category.') }}</div>
    </div>
    <div class="col-md-4">
        <label class="form-label" for="sort_order">{{ __('Order') }}</label>
        <input class="form-control" id="sort_order" type="number" min="0" max="9999" name="sort_order" value="{{ old('sort_order', $currentCategory?->sort_order ?? 0) }}">
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" id="is_active" type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $currentCategory?->is_active ?? true))>
            <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
        </div>
    </div>
</div>
