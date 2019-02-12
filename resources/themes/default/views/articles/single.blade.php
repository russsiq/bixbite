<article id="article-{{ $article->id }}" class="single_article single_article__{{ $article->category->slug }}" itemscope itemtype="http://schema.org/Article">
    <div class="single_article__inner">
        <header class="single_article__header">
            <div class="moder_panel">
                @can ('admin.articles.update', $article)
                    <a href="{{ route('admin.articles.edit', $article) }}" class=""><i class="fa fa-edit"></i></a>
                @endcan
                @role('owner')
                    <a href="{{ route('system_care.clearcache', 'articles-single-'.$article->id) }}" class=""><i class="fa fa-recycle"></i></a>
                @endrole
            </div>
            <h2 class="single_article__title" itemprop="headline">{{ $article->title }}</h2>
            <p class="single_article__teaser">{{ $article->teaser }}</p>
            @if ($image = $article->image)
                {{ $image->picture_box }}
            @endif
        </header>

        <section class="single_article__content">
            <div class="single_article__info">
                <span class="single_article__meta"><a href="{{ $article->user->profile }}" itemprop="author">{{ $article->user->name }}</a>,</span>
                <span class="single_article__meta">{{ $article->created }}</span>
                @if ($article->views)
                    <span class="single_article__meta-right"><i class="fa fa-eye"></i> {{ $article->views }}</span>
                @endif
                @if ($article->comments_count)
                    <span class="single_article__meta-right"><i class="fa fa-comments-o"></i> {{ $article->comments_count }}</span>
                @endif
            </div>

            <div class="single_article__body" itemprop="articleBody">{!! $article->content !!}</div>

            <div class="single_article__info">
                <span class="single_article__meta"><i class="fa fa-folder-open-o"></i>
                    {{ wrap_attr($article->categories, '<a href="%url" itemprop="articleSection" rel="category">%title</a>') }}
                </span>
                <span class="single_article__meta-right"><i class="fa fa-tags"></i>
                    {{ wrap_attr($article->tags, '<a href="%url" rel="tag">%title</a>') }}
                </span>
            </div>
        </section>
    </div>
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
