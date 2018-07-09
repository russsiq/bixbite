<li id="li-comment-{{ $comment->id }}" class="comment" itemscope itemtype="http://schema.org/Comment">
    <article id="comment-{{ $comment->id }}" class="comment__inner">
        <figure class="comment__avatar">
            <img src="{{ $comment->author->avatar }}" alt="{{ $comment->author->name }}" width="33px" class="comment_avatar__thumbnail" />
        </figure>
        <header class="comment__header {{ $comment->by_author ?  'by_author' : ''}}">
            <span class="comment__reply" data-respond="{{ $comment->id }}">@lang('comments.btn.reply')</span>
            @if ($comment->by_user)
                <a href="{{ $comment->user->profile }}" title="@lang('auth.profile')">
                    <i class="widget_item__title" itemprop="author">
                        {{ $comment->author->name }} <b class="{{ $comment->author->isOnline ? 'is_online' : '' }}"></b>
                    </i>
                </a>
            @else
                <i class="widget_item__title" itemprop="author">{{ $comment->author->name }}</i>
            @endif
            <p class="widget_item__subtitle">{{ $comment->created }}</p>
        </header>
        <div class="comment__content" itemprop="text">{!! $comment->content !!}</div>
    </article>

    @if($comment->children)
        <ul class="comments_list__children">
            @each('comments.show', $comment->children, 'comment')
        </ul>
    @endif
</li>
