<article id="article-{{ $article->id }}" class="short_article short_article__{{ $article->category->slug }}">
    <div class="short_article__inner">
        @if ($image = $article->image)
            <figure class="short_article__image">
                <picture class="short_article_image__inner">
                    <img src="{{ $image->getUrlAttribute('medium') ?? $image->getUrlAttribute('small') }}" alt="{{ $image->title }}" class="short_article_image__img" />
                </picture>
            </figure>
        @endif

        <section class="short_article__content">
            @can ('admin.articles.update', $article)
                <a href="{{ route('admin.articles.edit', $article) }}" class="moder_panel"><i class="fa fa-edit"></i></a>
            @endcan
            <a href="{{ $article->url }}" title="{{ $article->title }}">
                <h2 class="short_article__title">{{ $article->title }}</h2>
            </a>
            <p class="short_article__subtitle">
                <span class="short_article__meta">{{ $article->user->name }}, {{ $article->created }}</span>
                <span class="short_article__meta-right"><i class="fa fa-eye"></i> {{ $article->views }}</span>
                @if ($article->comments_count)
                    <span class="short_article__meta-right"><i class="fa fa-comments-o"></i> {{ $article->comments_count }}</span>
                @endif
            </p>
            <p class="short_article__teaser">{{ $article->teaser }}</p>
        </section>

        <div class="single_article__info w-100">
            <span class="single_article__meta"><i class="fa fa-folder-open-o"></i>
                {{ wrap_attr($article->categories, '<span class="cat-links"><a href="%url" rel="category">%title</a></span>') }}
            </span>
            <span class="single_article__meta-right">
                <a href="{{ $article->url }}" title="{{ $article->title }}" class="more-link">@lang('common.read_more')</a>
            </span>
        </div>
    </div>
</article>
