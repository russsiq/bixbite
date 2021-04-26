<nav class="navbar header-navbar" itemscope itemtype="http://www.schema.org/SiteNavigationElement">
    <div class="container-fluid">
        <a href="{{ route('home') }}" class="navbar-brand" rel="home">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

        <div id="navbarSupportedContent" class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                @each('components.partials.navigation', $categories->filter->show_in_menu->nested(), 'category')
            </ul>

            <!-- Authentication Links -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    @guest
                        <a id="navbarDropdownMenuLink" href="#" data-toggle="dropdown" class="nav-link dropdown-toggle"
                            role="button" aria-expanded="false">@lang('Profile') </a>
                        <ul class="dropdown-menu dropdown-menu-end" data-bs-popper="none" aria-labelledby="navbarDropdownMenuLink">
                            <li><a href="{{ route('login') }}" class="dropdown-item">@lang('Login')</a></li>
                            <li><a href="{{ route('register') }}" class="dropdown-item">@lang('Register')</a></li>
                        </ul>
                    @else
                        <a id="navbarDropdownMenuLink" href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle text-uppercase" role="button"
                            aria-expanded="false">{{ $user->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-end" data-bs-popper="none" aria-labelledby="navbarDropdownMenuLink">
                            @can ('global.dashboard')
                                <li><a href="{{ route('dashboard') }}" class="dropdown-item">@lang('Dashboard')</a></li>
                            @endcan
                            <li><a href="{{ route('profile.show') }}" class="dropdown-item">@lang('Profile')</a></li>
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit" class="dropdown-item">@lang('Logout')</button>
                                </form>
                            </li>
                        </ul>
                    @endguest
                </li>
            </ul>
        </div>
    </div>
</nav>
