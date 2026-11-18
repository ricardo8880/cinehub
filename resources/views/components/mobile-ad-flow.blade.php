{{-- Separate mobile units: desktop AdSense elements are never moved after initialization. --}}
<div class="mobile-flow-ad-source" data-mobile-ad-flow="{{ $kind }}" aria-label="{{ __('Advertisements') }}">
    @include('components.sidebar-ads', ['context' => $context])
</div>
<script src="{{ asset('js/mobile-experience.js') }}"></script>
