@props(['comment', 'formAction', 'reply' => false])
<article id="comment-{{ $comment->id }}" data-comment-id="{{ $comment->id }}" class="card public-article__section border-0 {{ $reply ? 'mt-2 ms-3 ms-md-5' : '' }}">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
            <strong>{{ $comment->author_name ?: $comment->user?->name ?: __('Member') }}</strong>
            <time class="small text-body-secondary" datetime="{{ $comment->created_at?->toIso8601String() }}">{{ $comment->created_at?->format('M j, Y') }}</time>
        </div>
        <div class="text-break">{!! nl2br(e($comment->body)) !!}</div>
        <div class="d-flex flex-wrap align-items-center gap-3 mt-3">
            @auth
                <form method="post" action="{{ route('comments.like', ['comment' => $comment->id]) }}" data-ajax-comment-like>@csrf
                    <button type="submit" class="btn btn-link text-body-secondary p-0 text-decoration-none small" aria-pressed="{{ $comment->likes->isNotEmpty() ? 'true' : 'false' }}"><i class="bi {{ $comment->likes->isNotEmpty() ? 'bi-heart-fill text-danger' : 'bi-heart' }} me-1" data-comment-like-icon></i><span data-comment-like-count>{{ $comment->likes_count ?? 0 }}</span> {{ __('Likes') }}</button>
                </form>
                <button type="button" class="btn btn-link text-body-secondary p-0 text-decoration-none small" data-bs-toggle="collapse" data-bs-target="#reply-{{ $comment->id }}" aria-expanded="false"><i class="bi bi-reply me-1"></i>{{ __('Reply') }}</button>
            @else
                <a class="btn btn-link text-body-secondary p-0 text-decoration-none small" href="{{ route('member.login', ['locale' => request()->route('locale'), 'return_to' => url()->current().'#comment-'.$comment->id]) }}" aria-label="{{ __('Sign in to like') }}" title="{{ __('Sign in to like') }}"><i class="bi bi-heart me-1" aria-hidden="true"></i>{{ $comment->likes_count ?? 0 }} {{ __('Likes') }}</a>
                <a class="btn btn-link text-body-secondary p-0 text-decoration-none small" href="{{ route('member.login', ['locale' => request()->route('locale'), 'return_to' => url()->current().'#comment-'.$comment->id]) }}" title="{{ __('Sign in to reply') }}"><i class="bi bi-reply me-1" aria-hidden="true"></i>{{ __('Reply') }}</a>
            @endauth
        </div>
        @auth
            <div class="collapse mt-3" id="reply-{{ $comment->id }}">
                <form method="post" action="{{ $formAction }}" data-moderated-comment data-preview-url="{{ route('member.comments.preview') }}" data-block-warning="{{ __('Your reply contains abusive or prohibited language. Edit it to continue.') }}" data-check-error="{{ __('Unable to check this reply. Please try again.') }}">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <label for="reply-body-{{ $comment->id }}" class="form-label small">{{ __('Reply to :name', ['name' => $comment->author_name]) }}</label>
                    <textarea id="reply-body-{{ $comment->id }}" name="body" class="form-control" rows="3" minlength="5" maxlength="2000" required>{{ old('parent_id') == $comment->id ? old('body') : '' }}</textarea>
                    <div class="small mt-1 text-danger" role="status" aria-live="polite" data-comment-warning hidden></div>
                    <div class="small mt-1 text-success" role="status" aria-live="polite" data-comment-success hidden></div>
                    <button class="btn btn-danger btn-sm mt-2" type="submit" data-comment-submit>{{ __('Send reply') }}</button>
                </form>
            </div>
        @endauth
    </div>
</article>
