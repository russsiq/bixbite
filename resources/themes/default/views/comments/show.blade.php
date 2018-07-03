<li id="li-comment-{{ $comment->id }}" class="comment" itemscope itemtype="http://schema.org/Comment">
    <article id="comment-{{ $comment->id }}" class="comment__inner">

        <figure class="comment__image">
            <img src="{{ $comment->author->avatar }}" alt="{{ $comment->author->name }}" width="33px" class="widget_item_image__thumbnail widget_comments-item-image__thumbnail" />
        </figure>

        <header class="widget_item__header widget_comments-item__header {{ $comment->by_author ?  'by_author' : ''}}">
            <span class="comment__reply_link" data-respond="{{ $comment->id }}">@lang('comments.btn.reply')</span>
            @if ($comment->by_user)
                <a href="#" title="@lang('auth.profile')" class="comment-author-link">
                    <i class="widget_item__title" itemprop="author">
                        {{ $comment->author->name }}
                        @if($comment->author->isOnline)
                            <b class="text-success" style="text-shadow: 0 0 8px #28a745;">&nbsp;•&nbsp;</b>
                        @endif
                    </i>
                </a>
            @else
                <i class="widget_item__title" itemprop="author">{{ $comment->author->name }}</i>
            @endif
            <p class="widget_item__subtitle">{{ $comment->created }}</p>
        </header>

        <p class="comment__content" itemprop="text">{{ $comment->content }}</p>
    </article>

    @if($comment->children)
        <ul class="comments_list__children">
            @each('comments.show', $comment->children, 'comment')
        </ul>
    @endif
</li>
