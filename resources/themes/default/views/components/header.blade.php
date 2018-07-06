<nav class="navbar header-navbar">
    <a href="{{ url('/') }}" class="navbar-brand" rel="home">{{ setting('system.app_name', 'BixBite') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

    <div id="navbarSupportedContent" class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            @if (pageinfo('navigation_categories'))
                @each('components.partials.categories', pageinfo('navigation_categories'), 'item')
            @endif
        </ul>
            <!-- Authentication Links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                @guest
                    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle">@lang('auth.profile') </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('login') }}" class="dropdown-item">@lang('auth.btn.login')</a>
                        <a href="{{ route('register') }}" class="dropdown-item">@lang('auth.btn.register')</a>
                    </div>
                @else
                    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle text-uppercase">{{ user('name') }} </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @can ('global.admin')
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">@lang('auth.dashboard')</a>
                        @endcan
                        @can ('admin.articles.create')
                            <a href="{{ route('admin.articles.create') }}" class="dropdown-item">@lang('articles.create')</a>
                        @endcan
                        <a href="{{ user()->profile }}" class="dropdown-item">@lang('auth.profile')</a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="post">
                            <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="dropdown-item">@lang('auth.logout')</button>
                        </form>
                    </div>
                @endguest
            </li>
        </ul>
    </div>
</nav>
