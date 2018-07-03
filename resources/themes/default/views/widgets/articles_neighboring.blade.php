<section class="widget widget_neighboring">
    <div class="widget__inner">
        <div class="widget__body widget_neighboring__body">
            @role('owner')
                <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder_panel">≡</a>
            @endrole
            <ul class="widget__list widget_neighboring__list">
                <li class="widget_neighboring__item">
                    @isset($widget->prev)
                        <a href="{{ $widget->prev->url }}" class="widget_neighboring__item-prev" rel="prev">←&nbsp;{{ $widget->prev->title }}</a>
                    @endisset
                </li>
                <li class="widget_neighboring__item">
                    @isset($widget->next)
                        <a href="{{ $widget->next->url }}" class="widget_neighboring__item-next" rel="next">{{ $widget->next->title }}&nbsp;→</a>
                    @endisset
                </li>
            </ul>
        </div>
    </div>
</section>
