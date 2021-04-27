<table class="profile_page__profile">
    <tbody>
        <tr><td>@lang('users.name')</td><td width="28"></td><td>{{ $user->name }}</td></tr>
        <tr><td>@lang('users.group')</td><td></td><td>@lang('users.role.'.$user->role)</td></tr>
        <tr><td>@lang('users.created_at')</td><td></td><td>{{ $user->created }}</td></tr>
        <tr><td>@lang('users.last_active')</td><td></td><td>{{ $user->lastActive() ?? __('users.never_been') }}</td></tr>
        <tr><td>@lang('users.articles_count')</td><td></td><td>{{ $user->articles_count ?? '...' }}</td></tr>
        <tr><td>@lang('users.comments_count')</td><td></td><td>{{ $user->comments_count ?? '...' }}</td></tr>
        <tr><td>@lang('users.where_from')</td><td></td><td>{{ $user->where_from ?? '...' }}</td></tr>
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
