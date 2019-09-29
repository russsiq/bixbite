<noscript>
    <div id="alert-message" class="alert alert-{{ $type ?? 'danger'}}">
        @isset($title)
            <h5 class="alert-heading">@lang($title)</h5>
        @endisset
        @lang($message)
    </div>
</noscript>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Notification.show({
                @isset($title)
                    title: '@lang($title)',
                @endisset
                message: '@lang($message)',
                type: '{{ $type ?? 'danger' }}'
            });
        });
    </script>
@endpush
