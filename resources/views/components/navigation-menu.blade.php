<nav {{ $attributes->merge(['class' => 'navbar navbar-expand-lg navbar-light bg-white shadow'])->filter(fn ($value, $key) => 'class' === $key) }}>
    <div class="{{ $container }}">
        <a href="{{ route('home') }}" class="navbar-brand">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="#" class="nav-link active" aria-current="page">Articles</a>
                </li>
            </ul>

            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a id="navbar-profile-dropdown" href="#" class="nav-link dropdown-toggle" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        @lang('Profile')
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbar-profile-dropdown">
                        @auth
                        <li><a href="{{ route('panel') }}" class="dropdown-item">@lang('Control Panel')</a></li>
                        <!-- Account Management -->
                        {{-- <li><h6 class="dropdown-header">@lang('Manage Account')</h6></li> --}}
                        <li><a href="{{ route('profile.show') }}" class="dropdown-item">@lang('Profile')</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <!-- Authentication -->
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <li><button type="submit" class="dropdown-item">@lang('Logout')</button></li>
                        </form>
                        @else
                        <li><a href="{{ route('login') }}" class="dropdown-item">@lang('Login')</a></li>
                        <li><a href="{{ route('register') }}" class="dropdown-item">@lang('Register')</a></li>
                        @endauth
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
