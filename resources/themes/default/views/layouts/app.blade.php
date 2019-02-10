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
        <div id="app" class="page" itemscope itemtype="http://schema.org/WebPage">
            @each('components.alert', $errors->all(), 'message')
            @if ($message = session('status') ?? session('message'))
                @include('components.alert', ['type' => 'success', 'message' => trim($message)])
            @endif
            <header class="page_header" itemscope itemtype="http://schema.org/WPHeader">
                @yield('sidebar_header')
                @yield('header')
            </header>

            <section class="page_body">
                <div class="inner-wrap">
                    <div class="page_body__wrap @yield('page-layout')">
                        @yield('sidebar_left')
                        <main class="mainblock">
                            @yield('mainblock')
                        </main>
                        @yield('sidebar_right')
                    </div>
                </div>
            </section>

            <footer class="page_footer" itemscope itemtype="http://schema.org/WPFooter">
                @yield('sidebar_footer')
                @yield('footer')
            </footer>
        </div>

        <script src="{{ theme_asset('js/app.js') }}"></script>
        @stack('scripts')
        
        <script type="application/ld+json">
            {
              "@context": "http://schema.org",
              "@type": "Organization",
              "name": "{{ setting('system.organization') }}",
              "url": "{{ setting('system.app_url') }}",
              "logo": "{{ theme_asset('favicon.ico') }}",
              "contactPoint": [{
                "@type": "ContactPoint",
                "telephone": "{{ setting('system.contact_telephone') }}",
                "contactType": "customer service",
                "email": "{{ setting('system.contact_email') }}",
                "availableLanguage": "RU",
                "areaServed" : "RU"
              }],
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "{{ setting('system.address_locality') }}",
                "streetAddress": "{{ setting('system.address_street') }}"
              }
            }
        </script>
    </body>
</html>
