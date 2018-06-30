<section class="alert-section">
    <noscript><div class="alert alert-warning">@lang('msg.alert_noscript')</div></noscript>
    @if ($errors->any())
        @each('components.alert', $errors->all(), 'message')
    @endif
    @if ($message = session('status') or $message = session('message'))
        @include('components.alert', ['type' => 'success', 'message' => trim($message)])
    @endif
</section>
