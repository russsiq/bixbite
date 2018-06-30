@push('scripts')
    <script>
    $(function() {
        $.notify({
            @isset($title)title: '<h5 class="alert-heading">@lang($title)</h5>', @endisset
             message: '@lang($message)'
         }, {type: '{{ $type or 'danger'}}'});
    });
    </script>
@endpush
<noscript>
    <div id="alert-message" class="alert alert-{{ $type or 'danger'}} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        @isset($title)<h5 class="alert-heading">@lang($title)</h5>@endisset
        @lang($message)
    </div>
</noscript>
