<section class="profile_page">
    <div class="profile_page__inner">
        <header class="profile_page__header">
            <div class="profile_page__picture" style="background-image:url('{{ theme_asset('images/cover.jpg') }}')">
                <div class="profile_page__picture_overlay"></div>
            </div>
            <div class="profile_page__box_layout">
                <figure class="profile_page__avatar_wrapper">
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" width="64" class="profile_page__avatar" />
                </figure>
                <div class="profile_page__name_wrapper">
                    <div class="profile_page__name">{{ $user->name }}</div>
                    <div class="profile_page__role">@lang('users.role.'.$user->role)</div>
                </div>

                <div class="profile_page__action">
                    @if (user('id') == $user->id)
                        {{-- Если это профиль текущего пользователя. --}}
                        <a href="{{ route('profile.edit', $user) }}" class="btn btn-outline-primary">@lang('users.btn.profile.edit')</a>
                    @elseif (user())
                        {{-- Если профиль просматривает зарегистрированный пользватель. --}}
                        <div class="dropdown">
                            <a href="#" data-toggle="dropdown" class="btn btn-outline-primary dropdown-toggle">Following</a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item">Follow</a>
                                <a href="#" class="dropdown-item">Private message</a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">Unfollow</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </header>


        {{-- <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a href="#profile" class="nav-link active" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">Profile</a>
            </li>
            <li class="nav-item">
                <a href="#wall" class="nav-link" data-toggle="tab" role="tab" aria-controls="profile">Wall</a>
            </li>
            <li class="nav-item">
                <a href="#friends" class="nav-link" data-toggle="tab" role="tab" aria-controls="contact">Friends</a>
            </li>
            <li class="nav-item">
                <a href="#media" class="nav-link" data-toggle="tab" role="tab" aria-controls="contact">Media</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="profile" class="tab-pane fade show active" role="tabpanel" aria-labelledby="home-tab">Profile</div>
            <div id="wall" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab">Wall</div>
            <div id="friends" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">Friends</div>
            <div id="media" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">Media</div>
        </div> --}}

        <table class="table table-sm profile_page__about">
            <tr><td>@lang('users.name')</td><td>{{ $user->name }}</td></tr>
            <tr><td>@lang('users.group')</td><td>@lang('users.role.'.$user->role)</td></tr>
            <tr><td>@lang('users.created_at')</td><td>{{ $user->created }}</td></tr>
            <tr><td>@lang('users.logined_at')</td><td>{{ $user->logined ?? 'Не был ни разу' }}</td></tr>
            <tr><td>@lang('users.where_from')</td><td>{{ $user->where_from ?? '...' }}</td></tr>
            <tr><td>@lang('users.info')</td><td>{{ $user->info ?? '...' }}</td></tr>
            <tr><td>@lang('users.articles_count')</td><td>{{ $user->articles_count ?? '...' }}</td></tr>
            <tr><td>@lang('users.comments_count')</td><td>{{ $user->comments_count ?? '...' }}</td></tr>
        </table>
    </div>
</section>
