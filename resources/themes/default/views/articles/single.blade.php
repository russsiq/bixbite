<article id="article-{{ $article->id }}" class="article single__article">
    <header class="article__header">
        <h2 class="article__title">{{ $article->title }}</h2>
        <p class="article__teaser">{{ $article->teaser }}</p>
        @if ($article->image)
            <figure class="article__image">
                {{ $article->image->picture_box }}
                <figcaption class="figure-caption text-right">{{ $article->image->title }}</figcaption>
            </figure>
        @endif
    </header>

    <section>
        {!! $article->content !!}
    </section>

    <footer>

    </footer>

    <div class="article-content clearfix">
        <div class="above-entry-meta">
            {{ wrap_attr($article->categories,
                '<span class="cat-links"><a href="%url" style="background:#888fce" rel="category">%title</a></span>',
                '&nbsp;') }}
        </div>
        <div class="below-entry-meta">
            <span class="posted-on">
                <i class="fa fa-calendar-o"></i>&nbsp;
                <time class="entry-date published" datetime="{{ $article->created_at->toIso8601String() }}" title="{{ $article->created_at }}">
                    {{ $article->created }}
                </time>
            </span>
            <span class="byline"><span class="author vcard"><i class="fa fa-user"></i> {{ $article->user->name }}</span></span>
            <span class="comments"><i class="fa fa-comment"></i> {{ $article->comments_count }}</span>
            <span class="comments"><i class="fa fa-eye"></i> {{ $article->views or '0' }}</span>
            <span class="tag-links"><i class="fa fa-tags"></i>
                {{ wrap_attr($article->tags, '<a href="%url" rel="tag">%title</a>', ', ') }}
            </span>
            @can ('admin.articles.update', $article)
                <span class="edit-link"><i class="fa fa-edit"></i><a class="post-edit-link" href="{{ route('admin.articles.edit', $article) }}">Edit</a></span>
            @endcan
        </div>
        <div class="entry-content clearfix"></div>
    </div>
</article>

@widget('articles.neighboring', [
    'active' => true,
])

@widget('articles.related', [
    'active' => true,
    // 'cache_time' => 0,
    // 'title' => 'Related',
    // 'limit' => 3,
    // 'template' => 'widgets.articles_related',
])

@include('comments.area', ['entity' => $article])
