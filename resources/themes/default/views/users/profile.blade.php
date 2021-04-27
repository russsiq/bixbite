<section class="profile_page">
    <div class="profile_page__inner">
        <header class="profile_page__header">
            <div class="profile_page__picture" style="background-image:url('{{ theme('images/cover.jpg') }}')">
                <div class="profile_page__picture_overlay"></div>
            </div>
            <div class="profile_page__box_layout">
                <figure class="profile_page__avatar_wrapper">
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" width="64" class="profile_page__avatar" />
                </figure>
                <div class="profile_page__name_wrapper">
                    <div class="profile_page__name">{{ $user->name }} <b class="{{ $user->isOnline() ? 'is_online' : '' }}"></b></div>
                    <div class="profile_page__role">@lang('users.role.'.$user->role)</div>
                </div>

                <div class="profile_page__action">
                    @if (auth()->guard()->check() && $user->id === auth()->guard()->user()->id)
                        {{-- Если это профиль текущего пользователя. --}}
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">@lang('Edit profile')</a>
                    @endif
                </div>
            </div>
        </header>

        <ul class="profile_page__tabs nav" role="tablist">
            <li class="profile_page_tabs__item">
                <a href="#profile" class="profile_page_tabs__link active" data-toggle="tab" role="tab">@lang('Profile')</a>
            </li>
            <li class="profile_page_tabs__item">
                <a href="{{ route('articles.index', ['user_id' => $user->id])}}" class="profile_page_tabs__link" role="tab">Articles</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="profile" class="tab-pane active" role="tabpanel">@include('users.partials.profile')</div>
        </div>
    </div>
</section>
