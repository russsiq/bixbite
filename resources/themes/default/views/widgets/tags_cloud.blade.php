<section class="widget">
    <div class="widget__inner">
        <div class="widget__header">
            @role('owner')
                <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder_panel">&#9776;</a>
            @endrole
            <h4 class="widget__title">{{ $widget->title }}</h4>
        </div>
        <div class="widget__body">
            @empty ($widget->items)
                @lang('common.msg.not_found')
            @else
                {{ wrap_attr($widget->items, '<a href="%url" class="mb-1 mr-1 btn btn-sm btn-outline-dark" rel="tag">%title</a>', ' ') }}
            @endforelse
        </div>
    </div>
</section>
