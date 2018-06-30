@extends(pageinfo('is_category') ? 'layouts.app' : 'layouts.app_fluid')

@section('mainblock')
    {{ $mainblock }}
@endsection

@section('sidebar_right')
    @include('components.sidebar_right')
@endsection

@section('sidebar_footer')
    @include('components.sidebar_footer')
@endsection

@section('footer')
    @include('components.footer')
@endsection