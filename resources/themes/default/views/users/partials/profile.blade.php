<table class="profile_page__profile">
    <tr><td>@lang('users.name')</td><td width="28"></td><td>{{ $user->name }}</td></tr>
    <tr><td>@lang('users.group')</td><td></td><td>@lang('users.role.'.$user->role)</td></tr>
    <tr><td>@lang('users.created_at')</td><td></td><td>{{ $user->created }}</td></tr>
    {{-- <tr><td>@lang('users.logined_at')</td><td></td><td>{{ $user->logined ?? __('users.never_been') }}</td></tr> --}}
    <tr><td>@lang('users.last_active')</td><td></td><td>{{ $user->lastActive() ?? __('users.never_been') }}</td></tr>
    <tr><td>@lang('users.articles_count')</td><td></td><td>{{ $user->articles_count ?? '...' }}</td></tr>
    <tr><td>@lang('users.comments_count')</td><td></td><td>{{ $user->comments_count ?? '...' }}</td></tr>
    <tr><td>@lang('users.where_from')</td><td></td><td>{{ $user->where_from ?? '...' }}</td></tr>
    <tr><td>@lang('users.info')</td><td></td><td>{{ $user->info ?? '...' }}</td></tr>
</table>
