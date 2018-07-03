<article id="article-{{ $article->id }}" class="single_article single_article__{{ $article->category->slug }}">
    <header class="single_article__header">
        @can ('admin.articles.update', $article)
            <a href="{{ route('admin.articles.edit', $article) }}" class="moder_panel"><i class="fa fa-edit"></i></a>
        @endcan
        <h2 class="single_article__title">{{ $article->title }}</h2>
        <p class="single_article__teaser">{{ $article->teaser }}</p>
    </header>

    @if ($article->image)
        <figure class="single_article__image">
            {{ $article->image->picture_box }}
            <figcaption class="single_article_image__caption">{{ $article->image->title }}</figcaption>
        </figure>
    @endif

    <section class="single_article__info">
        <span class="single_article__meta">{{ $article->user->name }},</span>
        <span class="single_article__meta">{{ $article->created }}</span>
        <span class="single_article__meta-right"><i class="fa fa-eye"></i> {{ $article->views }}</span>
        @if ($article->comments_count)
            <span class="single_article__meta-right"><i class="fa fa-comments-o"></i> {{ $article->comments_count }}</span>
        @endif
    </section>

    <section class="single_article__content">
        {!! $article->content !!}
    </section>

    <footer class="single_article__info">
        <span class="single_article__meta"><i class="fa fa-folder-open-o"></i>
            {{ wrap_attr($article->categories, '<a href="%url" rel="category">%title</a>','&nbsp;') }}
        </span>
        <span class="single_article__meta-right"><i class="fa fa-tags"></i>
            {{ wrap_attr($article->tags, '<a href="%url" rel="tag">%title</a>', ', ') }}
        </span>
    </footer>
</article>

@widget('articles.neighboring')

@widget('articles.related', [
    'active' => true,
    // 'cache_time' => 0,
    // 'title' => 'Related',
    // 'limit' => 3,
    // 'template' => 'widgets.articles_related',
])

@include('comments.area', ['entity' => $article])
