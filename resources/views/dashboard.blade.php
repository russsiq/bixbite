@extends('layouts.main')

@section('title', __('Dashboard'))

@push('styles')
<link href="{{ mix('/css/app.css') }}" rel="stylesheet">
@endpush

@section('body')
<noscript>
    <main class="container">
        <x-alert type="warning" class="mt-5">
            <x-slot name="heading">
                @lang('Внимание!')
            </x-slot>

            @lang('В вашем браузере отключен <b>JavaScript</b>. Для работы с административной панелью <b>включите его</b>.')
        </x-alert>
    </main>
</noscript>

<div id="app"></div>
@endsection

@push('scripts')
<script src="{{ mix('/js/app.js') }}" charset="utf-8"></script>
@endpush
