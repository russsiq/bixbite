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

    <link href="{{ theme('css/app.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon" />
    {{-- Стили, которые могут быть состыкованы из дочерних шаблонов. --}}
    @stack('styles')
</head>

<body>
    <div id="app" class="page" itemscope itemtype="http://schema.org/WebPage">
        <header class="page_header" itemscope itemtype="http://schema.org/WPHeader">
            @yield('sidebar_header')
            @yield('header')
        </header>

        <section class="page_body">
            <div class="inner-wrap">
                <div class="page_body__wrap @yield('page-layout')">
                    @yield('sidebar_left')
                    <main class="mainblock">
                        @each('components.alert', $errors->all(), 'message')

                        @if ($message = session('status') ?? session('message'))
                            @include('components.alert', [
                                'type' => 'success',
                                'message' => trim($message),
                            ])
                        @endif

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

        <template>
            {{-- Прелоадер для AJAX провайдера. --}}
            <loading-layer></loading-layer>
        </template>
    </div>

    {{-- Список всех скриптов шаблона. --}}
    <script>
        window.Pageinfo = {!! pageinfo()->scriptVariables() !!};
    </script>
    <script src="{{ theme('js/manifest.js') }}" charset="utf-8"></script>
    <script src="{{ theme('js/vendor.js') }}" charset="utf-8"></script>
    <script src="{{ theme('js/app.js') }}" charset="utf-8"></script>
    <script src="{{ theme('js/script.js') }}" charset="utf-8"></script>
    @g_recaptcha_script

    {{-- Скрипты, которые могут быть состыкованы из дочерних шаблонов. --}}
    @stack('scripts')

    {{-- Разметка schema.org сайта компании. --}}
    <script type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@type": "Organization",
          "name": "{{ setting('system.org_name') }}",
          "url": "{{ setting('system.app_url') }}",
          "logo": "{{ asset('favicon.ico') }}",
          "contactPoint": [{
            "@type": "ContactPoint",
            "telephone": "{{ setting('system.org_contact_telephone') }}",
            "contactType": "customer service",
            "email": "{{ setting('system.org_contact_email') }}",
            "availableLanguage": "RU",
            "areaServed" : "RU"
          }],
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "{{ setting('system.org_address_locality') }}",
            "streetAddress": "{{ setting('system.org_address_street') }}"
          }
        }
    </script>
</body>

</html>
