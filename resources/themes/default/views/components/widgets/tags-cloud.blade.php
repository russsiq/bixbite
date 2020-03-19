<section class="widget">
    <div class="widget__inner">
        <div class="widget__header">
            @role('owner')
                {{-- <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder_panel"><i class="fa fa-recycle"></i></a> --}}
            @endrole
            <h4 class="widget__title">{{ $title }}</h4>
        </div>
        <div class="widget__body">
            @empty ($tags)
                @lang('common.msg.not_found')
            @else
                {{ wrap_attr($tags, '<a href="%url" class="mb-1 mr-1 btn btn-sm btn-outline-dark" rel="tag">%title</a>', ' ') }}
            @endforelse
        </div>
    </div>
</section>
