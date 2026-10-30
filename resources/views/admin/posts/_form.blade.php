@php
    $currentPost = $post ?? null;
    $selectedCategories = collect(old('categories', $currentPost?->categories?->pluck('id')->all() ?? []))->map(fn ($id) => (int) $id)->all();
    $selectedGenres = collect(old('genres', $currentPost?->genres?->pluck('id')->all() ?? []))->map(fn ($id) => (int) $id)->all();
    $selectedBlogCategory = (int) old('blog_category_id', $currentPost?->blog_category_id ?? 0);
    $selectedBlogGenres = collect(old('blog_genres', $currentPost?->blogGenres?->pluck('id')->all() ?? []))->map(fn ($id) => (int) $id)->all();
    $publishedValue = old('published_at', $currentPost?->published_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i'));
    $publicationType = old('publication_type', $currentPost?->publication_type ?? (request('publication_type') === 'blog' ? 'blog' : 'standard'));
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

<input type="hidden" id="publication_type" name="publication_type" value="{{ $publicationType }}" data-publication-type>

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

@if ($publicationType !== 'blog')
<hr class="my-4">
<h2 class="h5">{{ __('Categories') }}</h2>
<div class="row g-2">
    @forelse ($categories as $category)
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="form-check border border-secondary-subtle rounded p-3 ps-5 h-100">
                <input class="form-check-input" id="category-{{ $category->id }}" type="radio" name="categories[]" value="{{ $category->id }}" data-post-category @checked(in_array($category->id, $selectedCategories, true))>
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
<h2 class="h5 mb-1">{{ __('Genres') }}</h2>
<p class="small text-body-secondary">{{ __('Only genres compatible with the selected category are shown.') }}</p>
<div class="row g-2" data-post-genres>
    @forelse ($genres as $genre)
        @php
            $genreCategoryIds = $genre->categories->pluck('id')->map(fn ($id) => (int) $id)->implode(',');
        @endphp
        <div class="col-12 col-sm-6 col-lg-4" data-genre-option data-category-ids="{{ $genreCategoryIds }}">
            <div class="form-check border border-secondary-subtle rounded p-3 ps-5 h-100">
                <input class="form-check-input" id="genre-{{ $genre->id }}" type="checkbox" name="genres[]" value="{{ $genre->id }}" @checked(in_array($genre->id, $selectedGenres, true))>
                <label class="form-check-label" for="genre-{{ $genre->id }}">{{ $genre->localization($defaultLocale)?->name ?? $genre->name }}</label>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-secondary mb-0">{{ __('No genres created yet.') }}</div></div>
    @endforelse
</div>

@endif

<div class="{{ $publicationType === 'blog' ? '' : 'd-none' }}" data-blog-taxonomy>
    <hr class="my-4">
    <h2 class="h5 mb-1">{{ __('Blog organization') }}</h2>
    <p class="small text-body-secondary">{{ __('The Blog category is internal. Visitors only see the Blog genres.') }}</p>

    <div class="d-flex flex-wrap gap-2 mb-3">
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.blog-categories.index') }}" target="_blank" rel="noopener"><i class="bi bi-journal-bookmark me-1"></i>{{ __('Manage Blog categories') }}</a>
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.blog-genres.index') }}" target="_blank" rel="noopener"><i class="bi bi-bookmarks me-1"></i>{{ __('Manage Blog genres') }}</a>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <label class="form-label" for="blog_category_id">{{ __('Blog category') }}</label>
            <select class="form-select" id="blog_category_id" name="blog_category_id" data-blog-category>
                <option value="">{{ __('Select a Blog category') }}</option>
                @foreach ($blogCategories as $blogCategory)
                    @php
                        $blogCategoryNames = collect($locales)->map(function ($settings, $locale) use ($blogCategory) {
                            $localized = $blogCategory->localization($locale);

                            return $localized
                                ? (($settings['country'] ?? strtoupper($settings['region'])).': '.$localized->name)
                                : null;
                        })->filter()->values();
                    @endphp
                    <option value="{{ $blogCategory->id }}" @selected($selectedBlogCategory === $blogCategory->id)>{{ $blogCategoryNames->isNotEmpty() ? $blogCategoryNames->implode(' | ') : $blogCategory->name }}</option>
                @endforeach
            </select>
            @if ($blogCategories->isEmpty())
                <div class="form-text text-warning">
                    {{ __('Create a Blog category before publishing Blog articles.') }}
                    <a href="{{ route('admin.blog-categories.create') }}" target="_blank" rel="noopener">{{ __('Create now') }}</a>
                </div>
            @endif
        </div>
        <div class="col-12 col-lg-7">
            <label class="form-label">{{ __('Blog genres') }}</label>
            <div class="row g-2" data-blog-genres>
                @forelse ($blogGenres as $blogGenre)
                    @php
                        $blogGenreCategoryIds = $blogGenre->categories->pluck('id')->map(fn ($id) => (int) $id)->implode(',');
                        $blogGenreName = $blogGenre->localization($defaultLocale)?->name ?? $blogGenre->localizations->first()?->name ?? '#'.$blogGenre->id;
                    @endphp
                    <div class="col-12 col-md-6" data-blog-genre-option data-blog-category-ids="{{ $blogGenreCategoryIds }}">
                        <div class="form-check border border-secondary-subtle rounded p-3 ps-5 h-100">
                            <input class="form-check-input" id="blog-genre-{{ $blogGenre->id }}" type="checkbox" name="blog_genres[]" value="{{ $blogGenre->id }}" @checked(in_array($blogGenre->id, $selectedBlogGenres, true))>
                            <label class="form-check-label" for="blog-genre-{{ $blogGenre->id }}">{{ $blogGenreName }}</label>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><div class="alert alert-secondary mb-0">{{ __('No Blog genres created yet.') }}</div></div>
                @endforelse
            </div>
        </div>
    </div>
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
            <div class="form-check form-switch mb-3" data-standard-post-fields>
                <input type="hidden" name="localizations[{{ $locale }}][is_featured]" value="0">
                <input class="form-check-input" id="featured-{{ $locale }}" type="checkbox" name="localizations[{{ $locale }}][is_featured]" value="1" @checked(old("localizations.$locale.is_featured", $localization?->is_featured ?? false))>
                <label class="form-check-label" for="featured-{{ $locale }}">{{ __('Featured in this country') }}</label>
            </div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="title-{{ $locale }}">{{ __('Title') }}</label>
                    <input class="form-control" id="title-{{ $locale }}" data-post-title="{{ $locale }}" name="localizations[{{ $locale }}][title]" value="{{ old("localizations.$locale.title", $localization?->title) }}" maxlength="180" {{ $locale === $defaultLocale ? 'required' : '' }}>
                </div>
                <div class="col-12" data-standard-post-fields>
                    <label class="form-label" for="excerpt-{{ $locale }}">{{ __('Excerpt') }}</label>
                    <div data-rich-text-wrapper>
                        <textarea class="form-control" id="excerpt-{{ $locale }}" data-rich-text-input data-rich-maxlength="500" data-post-excerpt="{{ $locale }}" name="localizations[{{ $locale }}][excerpt]" rows="3">{{ old("localizations.$locale.excerpt", $localization?->excerpt) }}</textarea>
                        <div data-rich-text-editor></div>
                        <div class="d-flex justify-content-between align-items-center gap-2 mt-2">
                            <button class="btn btn-outline-secondary btn-sm d-none" type="button" data-rich-text-source-toggle><i class="bi bi-code-slash me-1"></i>{{ __('HTML') }}</button>
                            <small class="text-body-secondary" data-rich-text-count></small>
                        </div>
                        <textarea class="form-control font-monospace mt-2 d-none" rows="6" data-rich-text-source spellcheck="false" aria-label="{{ __('HTML source') }}"></textarea>
                    </div>
                </div>
                <div class="col-12" data-blog-post-fields>
                    <label class="form-label" for="article-body-{{ $locale }}">{{ __('Article content') }}</label>
                    <div data-rich-text-wrapper data-rich-images data-rich-locale="{{ $locale }}" data-rich-image-upload-url="{{ route('admin.posts.article-images.store') }}">
                        <textarea class="form-control" id="article-body-{{ $locale }}" data-rich-text-input data-post-article-body="{{ $locale }}" name="localizations[{{ $locale }}][article_body]" rows="14">{{ old("localizations.$locale.article_body", $localization?->article_body) }}</textarea>
                        <div data-rich-text-editor></div>
                        <button class="btn btn-outline-secondary btn-sm mt-2 d-none" type="button" data-rich-text-source-toggle><i class="bi bi-code-slash me-1"></i>{{ __('HTML') }}</button>
                        <textarea class="form-control font-monospace mt-2 d-none" rows="16" data-rich-text-source spellcheck="false" aria-label="{{ __('HTML source') }}"></textarea>
                    </div>
                    <div class="form-text">{{ __('Write the article and insert images exactly where they should appear.') }}</div>
                </div>
                <div class="col-12" data-standard-post-fields>
                    <label class="form-label" for="synopsis-{{ $locale }}">{{ __('Synopsis') }}</label>
                    <div data-rich-text-wrapper>
                        <textarea class="form-control" id="synopsis-{{ $locale }}" data-rich-text-input data-post-synopsis="{{ $locale }}" name="localizations[{{ $locale }}][synopsis]" rows="5">{{ old("localizations.$locale.synopsis", $localization?->synopsis) }}</textarea>
                        <div data-rich-text-editor></div>
                        <button class="btn btn-outline-secondary btn-sm mt-2 d-none" type="button" data-rich-text-source-toggle><i class="bi bi-code-slash me-1"></i>{{ __('HTML') }}</button>
                        <textarea class="form-control font-monospace mt-2 d-none" rows="8" data-rich-text-source spellcheck="false" aria-label="{{ __('HTML source') }}"></textarea>
                    </div>
                </div>
                <div class="col-12" data-standard-post-fields>
                    <label class="form-label" for="review-{{ $locale }}">{{ __('Review') }}</label>
                    <div data-rich-text-wrapper>
                        <textarea class="form-control" id="review-{{ $locale }}" data-rich-text-input name="localizations[{{ $locale }}][review]" rows="8">{{ old("localizations.$locale.review", $localization?->review) }}</textarea>
                        <div data-rich-text-editor></div>
                        <button class="btn btn-outline-secondary btn-sm mt-2 d-none" type="button" data-rich-text-source-toggle><i class="bi bi-code-slash me-1"></i>{{ __('HTML') }}</button>
                        <textarea class="form-control font-monospace mt-2 d-none" rows="12" data-rich-text-source spellcheck="false" aria-label="{{ __('HTML source') }}"></textarea>
                    </div>
                </div>
                <div class="col-12" data-standard-post-fields>
                    <label class="form-label" for="spoilers-{{ $locale }}">{{ __('Spoilers') }}</label>
                    <textarea class="form-control" id="spoilers-{{ $locale }}" name="localizations[{{ $locale }}][spoilers]" rows="7">{{ old("localizations.$locale.spoilers", $localization?->spoilers) }}</textarea>
                    <div class="form-text">{{ __('Hidden by default on the public post. Visitors choose whether to reveal it.') }}</div>
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const publicationType = document.querySelector('[data-publication-type]');
    const standardFields = [...document.querySelectorAll('[data-standard-post-fields]')];
    const blogFields = [...document.querySelectorAll('[data-blog-post-fields]')];
    const blogTaxonomyFields = [...document.querySelectorAll('[data-blog-taxonomy]')];
    const blogCategorySelect = document.querySelector('[data-blog-category]');

    const updatePublicationFields = () => {
        const isBlog = publicationType?.value === 'blog';
        standardFields.forEach((field) => field.classList.toggle('d-none', isBlog));
        blogFields.forEach((field) => field.classList.toggle('d-none', !isBlog));
        blogTaxonomyFields.forEach((field) => field.classList.toggle('d-none', !isBlog));
        if (blogCategorySelect) blogCategorySelect.required = isBlog;
    };

    publicationType?.addEventListener('change', updatePublicationFields);
    updatePublicationFields();

    const categoryInputs = [...document.querySelectorAll('[data-post-category]')];
    const genreOptions = [...document.querySelectorAll('[data-genre-option]')];

    const updateGenreOptions = () => {
        const selectedCategoryIds = new Set(categoryInputs.filter((input) => input.checked).map((input) => input.value));

        genreOptions.forEach((option) => {
            const allowedCategoryIds = (option.dataset.categoryIds || '').split(',').filter(Boolean);
            const visible = allowedCategoryIds.some((id) => selectedCategoryIds.has(id));
            option.classList.toggle('d-none', !visible);

            if (!visible) {
                const checkbox = option.querySelector('input[type=\"checkbox\"]');
                if (checkbox) checkbox.checked = false;
            }
        });
    };

    categoryInputs.forEach((input) => input.addEventListener('change', updateGenreOptions));
    updateGenreOptions();

    const blogGenreOptions = [...document.querySelectorAll('[data-blog-genre-option]')];
    const updateBlogGenreOptions = () => {
        const selectedBlogCategoryId = blogCategorySelect?.value || '';

        blogGenreOptions.forEach((option) => {
            const allowedCategoryIds = (option.dataset.blogCategoryIds || '').split(',').filter(Boolean);
            const visible = selectedBlogCategoryId !== '' && allowedCategoryIds.includes(selectedBlogCategoryId);
            option.classList.toggle('d-none', !visible);

            if (!visible) {
                const checkbox = option.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = false;
            }
        });
    };

    blogCategorySelect?.addEventListener('change', updateBlogGenreOptions);
    updateBlogGenreOptions();

    const normalizeRichHtml = (html) => {
        const documentNode = document.implementation.createHTMLDocument('');
        const root = documentNode.createElement('div');
        root.innerHTML = html || '';

        const walker = documentNode.createTreeWalker(root, NodeFilter.SHOW_TEXT);
        let textNode;
        while ((textNode = walker.nextNode())) {
            textNode.nodeValue = (textNode.nodeValue || '').replace(/[\u00a0\u202f\u2007]/g, ' ');
        }

        root.querySelectorAll('strong, em, u, s, a').forEach((element) => {
            if ((element.textContent || '').replace(/[\u00a0\u202f\u2007]/g, ' ').trim() === '') {
                element.remove();
            }
        });

        root.querySelectorAll('p, blockquote, h2, h3, li').forEach((element) => {
            const text = (element.textContent || '').replace(/[\u00a0\u202f\u2007]/g, ' ').trim();
            const hasMeaningfulElement = [...element.children].some((child) => child.tagName !== 'BR');

            if (text === '' && !hasMeaningfulElement) {
                element.remove();
            }
        });

        root.querySelectorAll('ol, ul').forEach((element) => {
            if (!element.querySelector('li')) {
                element.remove();
            }
        });

        return root.innerHTML.trim();
    };

    const plainTextFromHtml = (html) => {
        const documentNode = document.implementation.createHTMLDocument('');
        const container = documentNode.createElement('div');
        container.innerHTML = html || '';
        return (container.textContent || '').replace(/\s+/g, ' ').trim();
    };

    document.querySelectorAll('[data-rich-text-wrapper]').forEach((wrapper) => {
        const input = wrapper.querySelector('[data-rich-text-input]');
        const editor = wrapper.querySelector('[data-rich-text-editor]');
        const source = wrapper.querySelector('[data-rich-text-source]');
        const toggle = wrapper.querySelector('[data-rich-text-source-toggle]');
        const count = wrapper.querySelector('[data-rich-text-count]');
        const maxLength = Number(input?.dataset.richMaxlength || 0);
        const allowImages = wrapper.hasAttribute('data-rich-images');
        const imageUploadUrl = wrapper.dataset.richImageUploadUrl || '';
        const richLocale = wrapper.dataset.richLocale || '';

        if (!input || !editor || !source || !toggle || typeof Quill === 'undefined') {
            return;
        }

        const toolbarOptions = [
            [{ header: [2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            allowImages ? ['blockquote', 'link', 'image'] : ['blockquote', 'link'],
            ['clean'],
        ];

        const formats = ['header', 'bold', 'italic', 'underline', 'strike', 'blockquote', 'list', 'link'];
        if (allowImages) formats.push('image');

        const quill = new Quill(editor, {
            theme: 'snow',
            formats,
            modules: { toolbar: toolbarOptions },
        });

        if (allowImages && imageUploadUrl) {
            quill.getModule('toolbar')?.addHandler('image', () => {
                const picker = document.createElement('input');
                picker.type = 'file';
                picker.accept = 'image/jpeg,image/png,image/webp,image/gif';
                picker.className = 'd-none';
                document.body.appendChild(picker);

                picker.addEventListener('change', async () => {
                    const file = picker.files?.[0];
                    if (!file) {
                        picker.remove();
                        return;
                    }

                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('locale', richLocale);

                    try {
                        const response = await fetch(imageUploadUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });
                        const payload = await response.json();

                        if (!response.ok || !payload.url) {
                            throw new Error(payload.message || '{{ __('Could not upload the image.') }}');
                        }

                        const altText = window.prompt('{{ __('Image description (alt text)') }}', '') ?? '';
                        const range = quill.getSelection(true) || { index: quill.getLength(), length: 0 };
                        quill.insertEmbed(range.index, 'image', payload.url, 'user');
                        quill.insertText(range.index + 1, '\n', 'silent');
                        const insertedImage = [...editor.querySelectorAll('img')].find((image) => image.getAttribute('src') === payload.url);
                        if (insertedImage) insertedImage.setAttribute('alt', altText.trim());
                        quill.setSelection(range.index + 2, 0, 'silent');
                        syncFromEditor();
                    } catch (error) {
                        window.alert(error?.message || '{{ __('Could not upload the image.') }}');
                    } finally {
                        picker.remove();
                    }
                }, { once: true });

                picker.click();
            });
        }

        input.classList.add('d-none');
        toggle.classList.remove('d-none');

        const updateCount = () => {
            if (!count || !maxLength) {
                return;
            }

            const length = plainTextFromHtml(input.value).length;
            count.textContent = `${length}/${maxLength}`;
            count.classList.toggle('text-danger', length > maxLength);
            count.classList.toggle('text-body-secondary', length <= maxLength);
        };

        const notifyInput = () => {
            input.dispatchEvent(new Event('input', { bubbles: true }));
            updateCount();
        };

        const syncFromEditor = () => {
            input.value = normalizeRichHtml(quill.getSemanticHTML());
            notifyInput();
        };

        const syncFromSource = () => {
            input.value = normalizeRichHtml(source.value);
            notifyInput();
        };

        if (input.value.trim() !== '') {
            quill.clipboard.dangerouslyPasteHTML(input.value);
        }
        syncFromEditor();

        quill.on('text-change', syncFromEditor);
        source.addEventListener('input', syncFromSource);

        toggle.addEventListener('click', () => {
            const sourceMode = !source.classList.contains('d-none');
            const toolbar = wrapper.querySelector('.ql-toolbar');

            if (sourceMode) {
                const cleanHtml = normalizeRichHtml(source.value);
                source.value = cleanHtml;
                quill.setText('');
                if (cleanHtml !== '') {
                    quill.clipboard.dangerouslyPasteHTML(cleanHtml);
                }
                syncFromEditor();
                source.classList.add('d-none');
                editor.classList.remove('d-none');
                toolbar?.classList.remove('d-none');
                toggle.innerHTML = '<i class="bi bi-code-slash me-1"></i>{{ __('HTML') }}';
                return;
            }

            source.value = normalizeRichHtml(quill.getSemanticHTML());
            syncFromSource();
            source.classList.remove('d-none');
            editor.classList.add('d-none');
            toolbar?.classList.add('d-none');
            toggle.innerHTML = '<i class="bi bi-pencil me-1"></i>{{ __('Editor') }}';
        });

        wrapper.closest('form')?.addEventListener('submit', () => {
            if (!source.classList.contains('d-none')) {
                syncFromSource();
            } else {
                syncFromEditor();
            }
        });
    });

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
        const articleBodyInput = document.querySelector(`[data-post-article-body="${locale}"]`);
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

            const excerpt = plainTextFromHtml(excerptInput?.value || '');
            const synopsis = plainTextFromHtml(synopsisInput?.value || '');
            const articleBody = plainTextFromHtml(articleBodyInput?.value || '');
            const title = titleInput.value.trim();
            const fallback = title ? `Read ${title} on {{ config('site.name') }}.` : '';
            seoDescriptionInput.value = (excerpt || synopsis || articleBody || fallback).slice(0, 170);
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
        articleBodyInput?.addEventListener('input', updateSeoDescription);

        updateSeoTitle();
        updateSeoDescription();
    });
});
</script>
@endpush
