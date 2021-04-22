<!DOCTYPE html>
<html lang="{{ pageinfo('locale') }}">

<head>
    <title>Панель управления - {{ config('app.name', 'BBCMS') }}</title>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="{{ dashboard('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon" />
    {{-- Стили, которые могут быть состыкованы из дочерних шаблонов. --}}
    @stack('styles')
</head>

<body>
    <noscript>
        <main class="container">
            <div class="alert alert-warning">
                <h5 class="alert-heading">Внимание!</h5>
                <p class="my-0">В вашем браузере отключен <b>JavaScript</b>. Для работы с административной панелью <b>включите его</b>.</p>
            </div>
        </main>
    </noscript>

    <div id="app"></div>

    {{-- Список всех скриптов шаблона. --}}
    <script type="text/javascript">
        window.Pageinfo = {!! pageinfo()->scriptVariables() !!}
    </script>
    <script src="{{ dashboard('js/manifest.js') }}" charset="utf-8"></script>
    <script src="{{ dashboard('js/vendor.js') }}" charset="utf-8"></script>
    <script src="{{ dashboard('js/app.js') }}" charset="utf-8"></script>

    {{-- Скрипты, которые могут быть состыкованы из дочерних шаблонов. --}}
    @stack('scripts')
</body>

</html>
