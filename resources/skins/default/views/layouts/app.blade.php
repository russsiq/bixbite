<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <title>Dashboard - {{ config('app.name', 'BBCMS') }}</title>
        <meta charset="utf-8" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link href="{{ skin_asset('css/app.css') }}" rel="stylesheet" />
        <link href="{{ skin_asset('favicon.ico') }}" rel="icon" />
        @stack('css')
        <style>.form-control{font-size:.9rem}.btn .fa{font-size:inherit}</style>
    </head>
    <body>
        <div id="app">
            @include('components.header')

            <main class="container">
                @yield('breadcrumb')
                @include('components.alert_section')
                @yield('mainblock')
            </main>

            @include('components.footer')
        </div>
        
        <!-- Scripts -->
        <script>
            window.Pageinfo = {!! pageinfo()->scriptVariables() !!}
        </script>
        <script src="{{ skin_asset('js/manifest.js') }}"></script>
        <script src="{{ skin_asset('js/vendor.js') }}"></script>
        <script src="{{ skin_asset('js/app.js') }}"></script>
        <script src="{{ skin_asset('js/engine.js') }}"></script>
        <script src="{{ skin_asset('js/script.js') }}"></script>
        @stack('scripts')
    </body>
</html>
