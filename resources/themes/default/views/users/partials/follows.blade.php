<ul>
    @forelse ($user->follows as $key => $follow)
        <li><a href="{{ $follow->profile }}">{{ $follow->name }}</a> - <a href="{{ route('unfollow', $follow) }}" class="pull-right">Unfollow</a></li>
    @empty
        @lang('common.msg.not_found')
    @endforelse
</ul>
