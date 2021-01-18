<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@lang("assistant::$master.headers.$stage") - @lang("assistant::assistant.sections.$master") - @lang('assistant::assistant.title')</title>
    <meta charset="utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    @include('assistant::_themes.default')

    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon" />
</head>

<body>
    <div id="app" class="container">
        <div class="assistant">
            <h1 class="assistant__header">@lang('assistant::assistant.title')</h1>
            <div class="assistant__body">
                <aside class="assistant__aside">
                    <ul class="aside__list">
                        @foreach (trans("assistant::assistant.sections") as $section => $title)
                            <li class="aside_list__item">
                                <a href="{{ route("assistant.$section.welcome") }}"
                                    class="aside_list__action {{ $section === $master ? 'active' : '' }} {{ ($section === 'install' and $installed) ? 'disabled' : '' }}"
                                >{{ $title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </aside>

                <main class="assistant__main">
                    <form action="{{ $action }}" method="post">
                        @csrf

                        <div class="form__header">
                            <h2>@lang("assistant::$master.headers.$stage")</h2>
                        </div>

                        <div class="form__body">
                            @yield('card_body')
                        </div>

                        <div class="form__footer">
                            @if ($installed && config('assistant.exit_route'))
                                <a href="{{ route(config('assistant.exit_route')) }}" class="btn">@lang('assistant::assistant.buttons.exit')</a>
                            @endif
                            <button type="submit" class="btn ml-auto">@lang('assistant::assistant.buttons.'.($onFinishStage ? 'finish' : 'next'))</button>
                        </div>
                    </form>
                </main>
            </div>
            {{-- <div class="assistant__footer"></div> --}}
        </div>
    </div>
</body>
</html>
