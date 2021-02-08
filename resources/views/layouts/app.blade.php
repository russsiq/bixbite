<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') – {{ config('app.name') }}</title>

    {{-- Styles --}}
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body class="d-flex flex-column h-100 bg-light">
    <x-navigation-menu container="container" />

    <header class="mt-4">
        <div class="container">
            <div class="row">
                <div class="col">
                    @yield('header')
                </div>
            </div>
        </div>
    </header>

    <main class="flex-shrink-0 mt-4">
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

    {{-- Scripts --}}
    <script src="{{ mix('/js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
