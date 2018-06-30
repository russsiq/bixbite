<!DOCTYPE html>
<html lang="{{ pageinfo('locale') }}">
    <head>
        <title>{{ pageinfo()->makeTitles() }}</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ pageinfo('csrf_token') }}" />
        @if(pageinfo('description'))<meta name="description" content="{{ pageinfo('description') }}" /> @endif
        @if(pageinfo('keywords'))<meta name="keywords" content="{{ pageinfo('keywords') }}" /> @endif
        @if(pageinfo('robots'))<meta name="robots" content="{{ pageinfo('robots') }}" /> @endif
        @if(pageinfo('url'))<link href="{{ pageinfo('url') }}" rel="canonical" /> @endif

        <link href="{{ theme_asset('css/app.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ theme_asset('favicon.ico') }}" rel="icon" />
        @stack('styles')
    </head>
    <body>
        <div id="app" class="page">
            @if ($errors->any()) @each('components.alert', $errors->all(), 'message') @endif
            @if ($message = session('status') or $message = session('message')) @include('components.alert', ['type' => 'success', 'message' => trim($message)]) @endif
            <header class="page-header">
                @yield('sidebar_header')
                @yield('header')
            </header>

            <section class="page-body">
                <div class="inner-wrap">
                    <div class="page-body__wrap @yield('page-layout')">
                        @yield('sidebar_left')
                        <main class="mainblock">
                            @yield('mainblock')
                        </main>
                        @yield('sidebar_right')
                    </div>
                </div>
            </section>

            <footer class="page-footer">
                @yield('sidebar_footer')
                @yield('footer')
            </footer>
            @include('components.page_appends')
        </div>

        <script src="{{ theme_asset('js/app.js') }}"></script>
        @stack('scripts')
    </body>
</html>
