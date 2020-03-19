@isset($articles)
<section class="widget widget_related">
    <div class="widget__inner">
        <div class="widget__header">
            @role('owner')
                {{-- <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder_panel"><i class="fa fa-recycle"></i></a> --}}
            @endrole
            <h4 class="widget__title">{{ $title }}</h4>
        </div>
        <div class="widget__body">
            <ul class="widget_related__list">
                @foreach($articles as $article)
                    <li class="widget_related__item">
                        <a href="{{ $article->url }}" class="widget_related_item__inner" rel="bookmark">
                            <figure class="widget_related_item__image">
                                @if ($image = $article->image)
                                    <img src="{{ $image->getUrlAttribute('thumb') }}" alt="{{ $image->title }}" class="widget_related_item_image__thumbnail" />
                                @endif
                            </figure>
                            <header class="widget_related_item__header">
                                <h5 class="widget_related_item__title">{{ $article->title }}</h5>
                                <p class="widget_related_item__subtitle">
                                    <span>{{ $article->updated ?? $article->created }}</span>
                                    @if ($article->views)
                                        <span><i class="fa fa-eye"></i> {{ $article->views }}</span>
                                    @endif
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
