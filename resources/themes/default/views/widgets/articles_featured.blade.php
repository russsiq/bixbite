<section class="widget">
    <div class="widget__inner">
        <div class="widget__header">
            @role('owner')
                <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder_panel">&#9776;</a>
            @endrole
            <h4 class="widget__title">{{ $widget->title }}</h4>
        </div>
        <div class="widget__body">
            <ul class="widget__list">
                @forelse($widget->items as $item)
                    <li class="widget__item">
                        <a href="{{ $item->url }}" title="{{ $item->title }}" class="widget_item__inner">
                            <figure class="widget_item__image widget_featured-item__image">
                                @if ($item->image)
                                    <img src="{{ $item->image->getUrlAttribute('thumb') }}" alt="{{ $item->image->title }}" class="widget_item_image__thumbnail widget_featured-item-image__thumbnail" />
                                @endif
                            </figure>
                            <header class="widget_item__header widget_featured-item__header">
                                <h5 class="widget_item__title">{{ $item->title }}</h5>
                                <p class="widget_item__subtitle">
                                    <span>{{ $item->updated ?? $item->created }}</span>
                                    @if ($item->views)
                                        <span><i class="fa fa-eye"></i> {{ $item->views }}</span>
                                    @endif
                                    {{-- <span class="pull-right"><i class="fa fa-comment"></i> {{ $item->comments_count }} •&nbsp;</span> --}}
                                </p>
                            </header>
                            {{-- <p class="widget_item__content">{{ $item->teaser }}</p> --}}
                        </a>
                    </li>
                @empty
                    @lang('common.msg.not_found')
                @endforelse
            </div>
        </ul>
    </div>
</section>
