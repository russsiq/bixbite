<noscript>
    <div id="alert-message" class="alert alert-{{ $type or 'danger'}} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        @isset($title)<h5 class="alert-heading">{{ $title }}</h5>@endisset
        {{ $message or $slot }}
    </div>
</noscript>
@push('scripts')
    <script type="text/javascript">
        $.notify({message:'{{ $message or $slot }}'},{type:'{{ $type or 'danger'}}'});
    </script>
@endpush
