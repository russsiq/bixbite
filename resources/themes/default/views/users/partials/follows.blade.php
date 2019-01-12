@if ($user->follows_count)
    <ul>
        @foreach ($user->follows as $key => $follow)
            <li>
                <a href="{{ $follow->profile }}">{{ $follow->name }}</a>
                @if (pageinfo('is_own_profile'))
                    <a href="{{ route('unfollow', $follow) }}" class="pull-right">Unfollow</a>
                @endif
            </li>
        @endforeach
    </ul>
@else
    @lang('common.msg.not_found')
@endif
