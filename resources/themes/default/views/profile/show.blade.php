@extends('auth')

@section('title', __('Profile'))

@section('action_page')
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
                    <div class="profile_page__name">{{ $user->name }} <b class="{{ $user->is_online ? 'is_online' : '' }}"></b></div>
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
            <div id="profile" class="tab-pane active" role="tabpanel">
                <table class="profile_page__profile">
                    <tbody>
                        <tr><td>@lang('users.name')</td><td width="28"></td><td>{{ $user->name }}</td></tr>
                        <tr><td>@lang('users.group')</td><td></td><td>@lang('users.role.'.$user->role)</td></tr>
                        <tr><td>@lang('users.created_at')</td><td></td><td>{{ $user->created }}</td></tr>
                        <tr><td>@lang('users.last_active')</td><td></td><td>{{ $user->last_active ?? __('users.never_been') }}</td></tr>
                        <tr><td>@lang('users.articles_count')</td><td></td><td>{{ $user->articles_count ?? '...' }}</td></tr>
                        <tr><td>@lang('users.comments_count')</td><td></td><td>{{ $user->comments_count ?? '...' }}</td></tr>
                        <tr><td>@lang('users.location')</td><td></td><td>{{ $user->location ?? '...' }}</td></tr>
                        <tr><td>@lang('users.info')</td><td></td><td>{{ $user->info ?? '...' }}</td></tr>
                        @if (count($x_fields))
                            @foreach ($x_fields as $x_field)
                                <tr><td>{{ $x_field->title }}</td><td></td>
                                    <td>
                                        @if ('array' == $x_field->type)
                                            @foreach ($x_field->params as $parameter)
                                                {{ $user->{$x_field->name} === $parameter['key'] ? $parameter['value'] : '' }}
                                            @endforeach
                                        @else
                                            {{ $user->{$x_field->name} ?? '...' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
