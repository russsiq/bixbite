<section class="widget widget_neighboring">
    <div class="widget__inner">
        <div class="widget__body widget_neighboring__body">
            {{-- Отобразим ссылку на очистку кеша только собственнику сайта. --}}
            @role('owner')
                <a href="{{ $clearCacheUrl }}" class="moder_panel"><i class="fa fa-recycle"></i></a>
            @endrole
            {{-- <h4 class="widget__title">{{ trans($title) }}</h4> --}}
            <ul class="widget__list widget_neighboring__list">
                <li class="widget_neighboring__item">
                    @if($previous = $previous())
                        <a href="{{ $previous->url }}" class="widget_neighboring__item-prev" rel="prev">←&nbsp;{{ $previous->title }}</a>
                    @endif
                </li>
                <li class="widget_neighboring__item">
                    @if($next = $next())
                        <a href="{{ $next->url }}" class="widget_neighboring__item-next" rel="next">{{ $next->title }}&nbsp;→</a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</section>
