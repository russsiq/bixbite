<header>
    <!-- Fixed navbar -->
    <nav class="navbar fixed-top navbar-expand navbar-dark bg-primary justify-content-between">
        <a class="navbar-brand" href="{{ url('/') }}" target="_blank">
            <i class="fa fa-external-link d-sm-none"></i> <span class="d-none d-sm-inline">{{ setting('system.app_name') }}</span></a>
        <button type="button" data-toggle="collapse" data-target="#navbar_main" class="navbar-toggler">
            <span class="navbar-toggler-icon"></span></button>

        <div id="navbar_main" class="collapse navbar-collapse">
            <ul class="navbar-nav d-none d-md-flex">
                <li class="nav-item"><a href="{{ route('admin.articles.index') }}" class="nav-link">@lang('articles')</a></li>
                <li class="nav-item"><a href="{{ route('admin.categories.index') }}" class="nav-link">@lang('categories')</a></li>
                <li class="nav-item"><a href="{{ route('admin.comments.index') }}" class="nav-link">@lang('comments')</a></li>
                <li class="nav-item -active"><a href="{{ route('admin.files.index') }}" class="nav-link">@lang('files')</a></li>
                <li class="nav-item"><a href="{{ route('admin.notes.index') }}" class="nav-link">@lang('notes')</a></li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item d-sm-none"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa fa-home"></i></a></li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" title="@lang('btn.add')" data-toggle="dropdown"><i class="fa fa-plus"></i> </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('admin.categories.create') }}" class="dropdown-item">@lang('category')</a>
                        <a href="{{ route('admin.articles.create') }}" class="dropdown-item">@lang('article')</a>
                        <a href="{{ route('admin.notes.create') }}" class="dropdown-item">@lang('note')</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" title="@lang('clear')" data-toggle="dropdown"><i class="fa fa-recycle"></i> </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('system_care.clearcache') }}" class="dropdown-item">@lang('Clear Cache')</a>
                        <a href="{{ route('system_care.clearviews') }}" class="dropdown-item">@lang('Clear Views')</a>
                        <a href="{{ route('system_care.optimize') }}" class="dropdown-item">@lang('Complex optimize')</a>
                    </div>
                </li>
                <li class="nav-item"><a href="{{-- route('admin.dashboard') --}}" title="@lang('help')" class="nav-link"><i class="fa fa-leanpub"></i> </a></li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="modal" data-target="#user_menu">
                        <img src="{{ user('avatar') }}" class="rounded-circle" alt="User Image" width="20" height="20">
                        <sup class="badge badge-pill badge-dark">3</sup>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Modal navbar #user_menu -->
    <div id="user_menu" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm -modal-dialog-centered" role="document">
            <div class="modal-content user-menu">
                <div class="user-header"><img src="{{ skin_asset('images/background_profile.jpg') }}"></div>
                <div class="user-avatar"><img src="{{ user('avatar') }}" class="rounded-circle" alt="User Image"></div>
                <div class="user-body"><p><b>{{ user('name') }}</b><br><small class="text-muted">@lang(user('role'))</small></p></div>
                <div class="user-footer">
                    <div class="pull-left"><a href="{{ route('admin.users.edit', user('id')) }}" class="btn btn-secondary">@lang('profile')</a></div>
                    <div class="pull-right">
                        <form action="{{ url('logout') }}" method="POST">
                            @csrf
                            <input type="submit" class="btn btn-secondary" value="@lang('logout')" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
