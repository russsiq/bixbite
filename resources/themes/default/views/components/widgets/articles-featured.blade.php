<section class="widget">
    <div class="widget__inner">
        <div class="widget__header">
            @role('owner')
                {{-- <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder_panel"><i class="fa fa-recycle"></i></a> --}}
            @endrole
            <h4 class="widget__title">{{ $title }}</h4>
        </div>
        <div class="widget__body">
            <ul class="widget__list">
                @forelse($articles as $article)
                    <li class="widget__item">
                        <a href="{{ $article->url }}" title="{{ $article->title }}" class="widget_item__inner">
                            <figure class="widget_item__image widget_featured-item__image">
                                @if ($article->image)
                                    <img src="{{ $article->image->getUrlAttribute('thumb') }}" alt="{{ $article->image->title }}" class="widget_item_image__thumbnail widget_featured-item-image__thumbnail" />
                                @endif
                            </figure>
                            <header class="widget_item__header widget_featured-item__header">
                                <h5 class="widget_item__title">{{ $article->title }}</h5>
                                <p class="widget_item__subtitle">
                                    <span>{{ $article->updated ?? $article->created }}</span>
                                    @if ($article->views)
                                        <span><i class="fa fa-eye"></i> {{ $article->views }}</span>
                                    @endif
                                    {{-- <span class="pull-right"><i class="fa fa-comment"></i> {{ $article->comments_count }} •&nbsp;</span> --}}
                                </p>
                            </header>
                            {{-- <p class="widget_item__content">{{ $article->teaser }}</p> --}}
                        </a>
                    </li>
                @empty
                    @lang('common.msg.not_found')
                @endforelse
            </div>
        </ul>
    </div>
</section>
