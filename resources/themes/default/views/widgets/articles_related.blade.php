@if($widget->items->count())
<section class="widget">
    <div class="widget__inner">
        <div class="widget__header">
            @role('owner')
                <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder_panel">≡</a>
            @endrole
            <h4 class="widget__title">{{ $widget->title }}</h4>
        </div>
        <div class="widget__body">
            <ul class="widget__list widget_related__list">
                @foreach($widget->items as $item)
                    <li class="widget_related__item">
                        <a href="{{ $item->url }}" class="widget_related-item__inner" rel="bookmark">
                            <figure class="widget_related-item__image">
                                @if ($item->image)
                                    <img src="{{ $item->image->getUrlAttribute('thumb') }}" alt="{{ $item->image->title }}" class="widget_related-item-image__thumbnail" />
                                @endif
                            </figure>
                            <header class="widget_related-item__header">
                                <h5 class="widget_item__title widget_related-item__title">{{ $item->title }}</h5>
                                <p class="widget_item__subtitle">
                                    <span>{{ $item->updated ?? $item->created }}</span>
                                    <span class="pull-right"><i class="fa fa-eye"></i> {{ $item->views }}</span>
                                </p>
                            </header>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
@endif
