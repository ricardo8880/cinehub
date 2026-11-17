@if (! in_array(request()->cookie('cinehub_analytics_consent'), ['granted', 'denied'], true) && request()->route('page') !== 'cookies')
    <div class="position-fixed bottom-0 start-0 end-0 mx-auto p-3" style="z-index: 1080; max-width: 850px;" role="region" aria-label="{{ __('Cookie Preferences') }}">
        <div class="rounded-3 shadow-lg border border-secondary bg-body p-3 d-flex flex-column flex-sm-row align-items-sm-center gap-3">
            <div class="flex-grow-1">
                <div class="fw-semibold">{{ __('Cookie Preferences') }}</div>
                <div class="small text-body-secondary">{{ __('Essential cookies are used for security and application sessions. Analytics remains optional.') }}</div>
                <a class="small" href="{{ route('legal.show', ['locale' => $currentLocale, 'page' => 'privacy']) }}">{{ __('Privacy') }}</a>
            </div>
            <form method="POST" action="{{ route('privacy.consent', ['locale' => $currentLocale]) }}" class="d-flex flex-wrap gap-2 flex-shrink-0">
                @csrf
                <button class="btn btn-danger btn-sm" type="submit" name="analytics" value="granted">{{ __('Allow analytics') }}</button>
                <button class="btn btn-outline-secondary btn-sm" type="submit" name="analytics" value="denied">{{ __('Reject analytics') }}</button>
            </form>
        </div>
    </div>
@endif
