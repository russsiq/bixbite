@extends('layouts.main')

@section('title', __('Dashboard'))

@push('meta-info')
<meta http-equiv="X-UA-Compatible" content="IE=edge">
@endpush

@push('styles')
<link href="{{ mix('/css/dashboard.css') }}" rel="stylesheet">
@endpush

@section('body')
<noscript>
    <main class="container">
        <x-alert type="warning" class="mt-5">
            <x-slot name="heading">
                @lang('Warning!')
            </x-slot>

            @lang("We're sorry but :app-name dashboard doesn't work properly without <b>JavaScript enabled</b>. Please enable it to continue.", [
                'app-name' => config('app.name')
            ])
        </x-alert>
    </main>
</noscript>

<div id="dashboard"></div>
@endsection

@push('scripts')
<script>
    const BixBite = @json($scriptVariables, JSON_PRETTY_PRINT);
</script>
<script src="{{ mix('/js/dashboard.js') }}" charset="utf-8"></script>
@endpush
