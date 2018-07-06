<section class="widget">
    <div class="widget__inner">
        <div class="widget__header">
            @role('owner')
                <a href="{{ route('system_care.clearcache', ['key' => $widget->cache_key]) }}" class="moder_panel">≡</a>
            @endrole
            <h4 class="widget__title">{{ $widget->title }}</h4>
        </div>
        <div class="widget__body">
            <ul class="widget__list">
                @forelse($widget->items as $item)
                    <li class="widget__item">
                        <a href="{{ $item->article->url}}#comment-{{ $item->id }}" class="widget_item__inner">
                            <figure class="widget_item__image widget_comments-item__image">
                                <img src="{{ $item->author->avatar }}" alt="{{ $item->author->name }}" width="33px" class="widget_item_image__thumbnail widget_comments-item-image__thumbnail" />
                            </figure>
                            <header class="widget_item__header widget_comments-item__header">
                                <i class="widget_item__title">{{ $item->author->name }}</i>
                                <p class="widget_item__subtitle">{{ $item->created }}</p>
                            </header>
                            <p class="widget_item__content widget_comments-item__content">
                                <span class="widget_item__title">{{ $item->article->title }}</span>
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
