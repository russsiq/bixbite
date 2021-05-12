<section class="widget">
    <div class="widget__inner">
        <div class="widget__header">
            {{-- Отобразим ссылку на очистку кеша только собственнику сайта. --}}
            @role('owner')
                <a href="{{ $clearCacheUrl }}" class="moder_panel"><i class="fa fa-recycle"></i></a>
            @endrole
            <h4 class="widget__title">{{ $title }}</h4>
        </div>
        <div class="widget__body">
            @empty ($tags)
                @lang('common.msg.not_found')
            @else
                {{ wrap_attr($tags, '<a href="%url" class="mb-1 me-1 btn btn-sm btn-outline-dark" rel="tag">%title</a>', ' ') }}
            @endforelse
        </div>
    </div>
</section>
