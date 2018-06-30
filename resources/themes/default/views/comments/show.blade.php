<li id="li-comment-{{ $comment->id }}" class="comment clearfix" itemscope="" itemtype="http://schema.org/Comment">
    <article id="comment-{{ $comment->id }}" class="comment-content">
        <img src="{{ $comment->author->avatar }}" alt="{{ $comment->author->name }}" class="rounded-circle pull-left" width="78" />
        <div class="comment-meta">
            <a href="#respond" rel="nofollow" class="comment-reply-link" data-respond="{{ $comment->id }}"><i class="fas fa-reply"></i> @lang('comments.btn.reply')</a>
            {{-- <a href="#" class="comment-reply-link"><span>@lang('edit')</span></a>
            <a href="#" onclick="return confirmIt('_______', '@lang('sure_del')')" class="comment-reply-link"><span>@lang('delete')</span></a> --}}
            <div class="comment-author {{ $comment->by_user ?  'by_user' : '' }} {{ $comment->by_author ?  'by_author' : ''}}">
                @if ($comment->by_user)
                    <a href="#" title="@lang('auth.profile')" class="comment-author-link"><span itemprop="author">{{ $comment->author->name }}</span></a>
                @else
                    <span itemprop="author">{{ $comment->author->name }}</span>
                @endif
                <small class="text-muted h5">@if($comment->author->isOnline) <b class="text-success" style="text-shadow: 0 0 8px #28a745;">&nbsp;•&nbsp;</b> @else &nbsp;•&nbsp; @endif</small>
            </div>
            <div class="comment-date"><small> {{ $comment->created }}</small></div>
            <p itemprop="text">{{ $comment->content }}</p>
        </div>
    </article><!-- #comment-## -->

    @if($comment->children)
        <ul class="children">
            @each('comments.show', $comment->children, 'comment')
        </ul><!-- .children -->
    @endif
</li>
