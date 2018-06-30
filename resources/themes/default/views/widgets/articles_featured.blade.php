<section class="widget">
    <div class="widget__inner">
        <div class="widget__header">
            @role('owner')
                <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder-panel pull-right">≡</a>
            @endrole
            <h4 class="widget__title">{{ $widget->title }}</h4>
        </div>
        <div class="widget__body">
            <ul class="widget__list">
                @forelse($widget->items as $item)
                    <li class="widget__item">
                        <a href="{{ $item->url }}" class="widget-item__inner">
                            <figure class="widget-item__image widget-featured-item__image">
                                @if ($item->image)
                                    <img src="{{ $item->image->getUrlAttribute('thumb') }}" alt="{{ $item->image->title }}" class="widget-item-image__thumbnail widget-featured-item-image__thumbnail" />
                                @endif
                            </figure>
                            <header class="widget-item__header widget-featured-item__header">
                                <h5 class="widget-item__title">{{ $item->title }}</h5>
                                <p class="widget-item__subtitle">
                                    <span>{{ $item->updated ?? $item->created }}</span>
                                    <span class="pull-right"><i class="fa fa-eye"></i> {{ $item->views }}</span>
                                    {{-- <span class="pull-right"><i class="fa fa-comment"></i> {{ $item->comments_count }} •&nbsp;</span> --}}
                                </p>
                            </header>
                            {{-- <p class="widget-item__content">{{ $item->teaser }}</p> --}}
                        </a>
                    </li>
                @empty
                    @lang('common.msg.not_found')
                @endforelse
            </div>
        </ul>
    </div>
</section>
