<div class="clearfix" style="position: relative;">
    <ul class="list-unstyled clearfix mb-0">
        @role('owner')
            <li style="position:absolute; right: 8px; top: 8px;">
                <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="pull-right">≡</a>
            </li>
        @endrole

        @isset($widget->previous)
            <li class="previous">
                <a href="{{ $widget->previous->url }}" rel="prev"><span class="meta-nav">&laquo;</span> {{ $widget->previous->title }}</a>
            </li>
        @endisset
        @isset($widget->next)
            <li class="next">
                <a href="{{ $widget->next->url }}" rel="next">{{ $widget->next->title }} <span class="meta-nav">&raquo;</span></a>
            </li>
        @endisset
    </ul><!-- #prev and next -->
</div>
