<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">

    <title>
        @hasSection('title')
        @yield('title') –
        @endif
        {{ config('app.name') }}
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    @stack('meta-info')

    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon">
    @stack('styles')
</head>

<body class="d-flex flex-column h-100 bg-light">
    @yield('body')
    @stack('scripts')
</body>

</html>
