<div class="widget__beside_slider">
    <div class="widget__beside_slider__inner">
        @role('owner')
            <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder_panel"><i class="fa fa-recycle"></i></a>
        @endrole
        @foreach ($widget->items as $key => $item)
            <a href="{{ $item->url }}" title="{{ $item->title }}" class="widget__beside_slider__item">
                <div class="widget__beside_slider__image" style="background-image: url({{ $item->image ? $item->image->getUrlAttribute('small') : '' }})"></div>
                <div class="widget__beside_slider__caption">
                    <h5 class="widget__beside_slider__title">{{ $item->title }}</h5>
                    <p class="widget__beside_slider__descr"><i class="fa fa-calendar-o"></i>&nbsp; {{ $item->created }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
