<nav class="navbar header-navbar" itemscope itemtype="http://www.schema.org/SiteNavigationElement">
    <div class="container-fluid">
        <a href="{{ route('home') }}" class="navbar-brand" rel="home">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

        <div id="navbarSupportedContent" class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                @each('components.partials.navigation', $categories->filter->show_in_menu->nested(), 'category')
            </ul>

            <!-- Authentication Links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    @guest
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle">@lang('Profile') </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="{{ route('login') }}" class="dropdown-item">@lang('Login')</a>
                            <a href="{{ route('register') }}" class="dropdown-item">@lang('Register')</a>
                        </div>
                    @else
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle text-uppercase">{{ $user->name }} </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            @can ('global.dashboard')
                                <a href="{{ route('dashboard') }}" class="dropdown-item">@lang('Dashboard')</a>
                            @endcan
                            <a href="{{ route('profile.show') }}" class="dropdown-item">@lang('Profile')</a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item">@lang('Logout')</button>
                            </form>
                        </div>
                    @endguest
                </li>
            </ul>
        </div>
    </div>
</nav>
