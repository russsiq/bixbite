<section class="widget widget_neighboring">
    <div class="widget__inner">
        <div class="widget__body widget_neighboring__body">
            @role('owner')
                {{-- <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder_panel"><i class="fa fa-recycle"></i></a> --}}
            @endrole
            <ul class="widget__list widget_neighboring__list">
                <li class="widget_neighboring__item">
                    @isset($previous)
                        <a href="{{ $previous->url }}" class="widget_neighboring__item-prev" rel="prev">←&nbsp;{{ $previous->title }}</a>
                    @endisset
                </li>
                <li class="widget_neighboring__item">
                    @isset($next)
                        <a href="{{ $next->url }}" class="widget_neighboring__item-next" rel="next">{{ $next->title }}&nbsp;→</a>
                    @endisset
                </li>
            </ul>
        </div>
    </div>
</section>
