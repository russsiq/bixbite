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
                        <a href="{{ $item->article->url}}#li-comment-{{ $item->id }}" class="widget-item__inner">
                            <figure class="widget-item__image widget-comments-item__image">
                                <img src="{{ $item->author->avatar }}" alt="{{ $item->author->name }}" width="33px" class="widget-item-image__thumbnail widget-comments-item-image__thumbnail" />
                            </figure>
                            <header class="widget-item__header widget-comments-item__header">
                                <b class="widget-item__title">{{ $item->author->name }}</b>
                                <p class="widget-item__subtitle">{{ $item->created }}</p>
                            </header>
                            <p class="widget-item__content widget-comments-item__content">
                                <span class="widget-item__title">{{ $item->article->title }}</span>
                                <i>{{ teaser($item->content, 68) }}</i>
                            </p>
                        </a>
                    </li>
                @empty
                    @lang('common.msg.not_found')
                @endforelse
            </ul>
        </div>
    </div>
</section>
