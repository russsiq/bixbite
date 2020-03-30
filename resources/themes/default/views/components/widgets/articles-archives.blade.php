<section class="widget">
    <div class="widget__inner">
        <div class="widget__header">
            {{-- Отобразим ссылку на очистку кеша только собственнику сайта. --}}
            @role('owner')
                <a href="{{ $clearCacheUrl }}" class="moder_panel"><i class="fa fa-recycle"></i></a>
            @endrole
            <h4 class="widget__title">{{ trans($title) }}</h4>
        </div>
        <div class="widget__body">
            <ul class="widget__list">
                @forelse($months as $date)
                    <li class="widget__item">
                        <a href="{{ route('articles.index', ['year'=> $date->year, 'month' => $date->month]) }}">
                            <span>{{ trans($date->month) }} {{ $date->year }}</span>
                            @if ($date->count)
                                <small class="pull-right">{{ $date->count }} {{ trans_choice('articles.num', $date->count) }}</small>
                            @endif
                        </a>
                    </li>
                @empty
                    @lang('common.msg.not_found')
                @endforelse
            </ul>
        </div>
    </div>
</section>
