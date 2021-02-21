<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @hasSection('title')
            @yield('title') –
        @endif
        {{ config('app.name') }}
    </title>

    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon" />

    {{-- Styles --}}
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

    {{-- Стили, которые могут быть состыкованы из дочерних шаблонов. --}}
    @stack('styles')
</head>

<body class="d-flex flex-column h-100 bg-light">
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

    {{-- Scripts --}}
    <script src="{{ mix('/js/app.js') }}" charset="utf-8"></script>

    {{-- Скрипты, которые могут быть состыкованы из дочерних шаблонов. --}}
    @stack('scripts')
</body>

</html>
