@if($widget->items->count())
<section class="widget">
    <div class="widget__inner">
        <div class="widget__header">
            @role('owner')
                <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder-panel pull-right">≡</a>
            @endrole
            <h4 class="widget__title">{{ $widget->title }}</h4>
        </div>
        <div class="widget__body">
            <ul class="widget__list widget-related__list">
                @foreach($widget->items as $item)
                    <li class="widget-related__item">
                        <a href="{{ $item->url }}" class="widget-related-item__inner" rel="bookmark">
                            <figure class="widget-related-item__image">
                                @if ($item->image)
                                    <img src="{{ $item->image->getUrlAttribute('thumb') }}" alt="{{ $item->image->title }}" class="widget-related-item-image__thumbnail" />
                                @endif
                            </figure>
                            <header class="widget-related-item__header">
                                <h5 class="widget-item__title widget-related-item__title">{{ $item->title }}</h5>
                                <p class="widget-item__subtitle">
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
