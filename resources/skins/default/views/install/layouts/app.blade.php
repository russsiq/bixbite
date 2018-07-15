<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <title>@yield('action_title') - {{ __('header.title') }}</title>
        <meta charset="utf-8" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link href="{{ skin_asset('css/app.css') }}" rel="stylesheet" />
        <link href="{{ skin_asset('css/install.css') }}" rel="stylesheet" />
        <link href="{{ skin_asset('favicon.ico') }}" rel="icon" type="image/x-icon">
    </head>
    <body>
        <main role="main" class="container">
            {{-- <div class="row">
        		<div class="col-sm-10 offset-sm-1">
                    @include('install.components.alert_section')
                </div>
            </div> --}}
        	<div class="row">
        		<div class="col-sm-10 offset-sm-1">

                    <div class="card card-form">
                    	<form action="{{ route('system.install.step_choice', ['action' => $action]) }}" method="post">
                            @csrf

                    		<div class="card-header d-flex justify-content-around">
                                <div class="form-progress">
                                    <div class="form-progress-line" style="width: {{ round(100 * $curstep / count($steps), 5) }}%;"></div>
                                </div>
                                @foreach ($steps as $key => $step)
                                    <div class="form-step{{ $key == $curstep ? ' active' : '' }}{{ $key < $curstep ? ' activated' : '' }}">
                                        <div class="form-step-icon"></div>
                                        <p>@lang('header.menu.' . $step)</p>
                                    </div>
                                @endforeach
                    		</div>

                            <div class="card-body">
                                @yield('card_body')
                            </div>

                    		<div class="card-footer d-flex">
                                @if (1 == $curstep)
                                    <div class="btn-group">
                                        <a href="{{ route('system.install.step_choice', ['app_locale'=>'ru']) }}" class="btn">Русский</a>
                                        {{-- <a href="{{ route('system.install.step_choice', ['app_locale'=>'en']) }}" class="btn">English</a> --}}
                                    </div>
                                    <button type="submit" class="btn ml-auto">{{ __('btn.continue') }} &raquo;</button>
                                @else
                                    <div class="btn- group ml-auto">
                                        @if ((count($steps) - 1) == $curstep)
                                            <button type="submit" class="btn">{{ __('btn.finish') }} &raquo;</button>
                                        @elseif (count($steps) > $curstep)
                                            <button type="submit" class="btn">{{ __('btn.next') }} &raquo;</button>
                                        @elseif (count($steps) == $curstep)
                                            <a href="{{ route('dashboard') }}" class="btn">{{ __('btn.continue') }} &raquo;</a>
                                        @endif
                                    </div>
                                @endif
                    		</div>
                    	</form>
                    </div>
                </div>
            </div>
        </main>
        <!-- Scripts -->
        <script src="{{ skin_asset('js/app.js') }}" type="text/javascript"></script>
    </body>
</html>
