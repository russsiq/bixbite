<nav {{ $attributes->merge(['class' => 'navbar navbar-expand-lg navbar-light bg-white shadow'])->filter(fn ($value, $key) => 'class' === $key) }}>
    <div class="{{ $container }}">
        <a href="{{ route('home') }}" class="navbar-brand">{{ config('app.name') }}</a>

        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#main-navbar"
            aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="main-navbar" class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @foreach ($categories as $category)
                <li class="nav-item">
                    <a href="#" class="nav-link active">{{ $category->title }}</a>
                </li>
                @endforeach
            </ul>

            <ul class="navbar-nav mb-2 mb-lg-0">
                @auth
                <li class="nav-item dropdown">
                    <a id="navbar-profile-dropdown" href="#" class="nav-link dropdown-toggle" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $user->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbar-profile-dropdown">
                        <li><a href="{{ route('dashboard') }}" class="dropdown-item">@lang('Dashboard')</a></li>
                        <!-- Account Management -->
                        <li><span class="dropdown-header">@lang('Manage Account')</span></li>
                        <li><a href="{{ route('profile.show') }}" class="dropdown-item">@lang('Profile')</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <!-- Authentication -->
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <li><button type="submit" class="dropdown-item">@lang('Logout')</button></li>
                        </form>
                    </ul>
                </li>
                @else
                <li class="nav-item me-3">
                    <a href="{{ route('login') }}" class="nav-link">@lang('Login')</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">@lang('Register')</a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
