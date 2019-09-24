{{-- Наличие идентификатора комментария `id="comment-{{ $comment->id }}"` обязательно. --}}
<li id="comment-{{ $comment->id }}" class="comment" itemscope itemtype="http://schema.org/Comment">
    <article class="comment__inner">
        <figure class="comment__avatar">
            <img src="{{ $comment->author->avatar }}" alt="{{ $comment->author->name }}" width="33" class="comment_avatar__thumbnail" />
        </figure>

        <header class="comment__header {{ $comment->by_author ?  'by_author' : ''}}">
            {{-- Класс `comment__reply` и атрибут `data-reply` используется JavaScript. --}}
            <span class="comment__reply" data-reply="{{ $comment->id }}">@lang('comments.btn.reply')</span>
            @if ($comment->by_user)<a href="{{ $comment->author->profile }}" title="@lang('auth.profile')">@endif
                <i class="widget_item__title" itemprop="creator">
                    {{ $comment->author->name }} <b class="{{ $comment->author->isOnline ? 'is_online' : '' }}"></b>
                </i>
            @if ($comment->by_user)</a>@endif
            <p class="widget_item__subtitle">{{ $comment->created }}</p>
        </header>

        {{-- Vue падает если в комментарии содержатся скобки `{{...}}`, поэтому отключаем обработку, добавив тег `v-pre`. --}}
        <div class="comment__content" itemprop="text" v-pre>{!! $comment->content !!}</div>

        {{-- Управление комментарием. --}}
        @can ('comments.update', $comment)
            <a href="{{ route('comments.edit', $comment) }}" class="btn btn-link">@lang('common.btn.edit')</a>
        @endcan
        @can ('comments.delete', $comment)
            <form action="{{ route('comments.delete', $comment) }}" method="post" onsubmit="return confirm('@lang('common.msg.sure_del')');">
                <input type="hidden" name="_method" value="DELETE" />
                <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-link">@lang('common.btn.delete')</button>
            </form>
        @endcan
    </article>

    {{--
        Дочерние комментарии должны быть обернуты в один родительский тег,
        который располагается последним в иерархии DOM текущего комментария.
    --}}
    <ul class="comments_list__children">
        @each('comments.show', $comment->children, 'comment')
    </ul>
</li>
