@extends('layouts.main')

@push('styles')
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ mix('/js/app.js') }}" charset="utf-8"></script>
@endpush

@section('body')
    <x-navbar container="container" />

    @hasSection('header')
    <header class="my-4">
        <div class="container">
            <div class="row">
                <div class="col">
                    @yield('header')
                </div>
            </div>
        </div>
    </header>
    @endif

    <main class="flex-shrink-0">
        @yield('mainblock')
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <div class="row">
                <div class="col">
                    <span class="text-muted">Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
                </div>
                <div class="col text-end">
                    <span class="text-muted">(PHP v{{ PHP_VERSION }})</span>
                </div>
            </div>
        </div>
    </footer>

    <x-consent-cookie />
@endsection
