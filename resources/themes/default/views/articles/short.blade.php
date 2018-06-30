<article id="article-{{ $article->id }}" class="post type-post status-publish format-standard has-post-thumbnail hentry">
    @if ($article->image)
        <div class="article-item-img">
            <a href="{{ $article->url }}">
                <img src="{{ $article->image->getUrlAttribute('medium') ?? $article->image->getUrlAttribute('small') }}" alt="{{ $article->image->title }}" />
            </a>
        </div>
    @endif

    <div class="article-content clearfix">
        <div class="above-entry-meta">
            {{ wrap_attr(
                $article->categories,
                '<span class="cat-links"><a href="%url" style="background:#dd5a5a" rel="category">%title</a></span>',
                '&nbsp;'
                ) }}
        </div>
        <header class="entry-header">
            <h2 class="entry-title">
                <a href="{{ $article->url }}">{{ $article->title }}</a>
            </h2>
        </header>
        <div class="below-entry-meta">
            <span class="posted-on"><i class="fa fa-calendar-o"></i> <time class="entry-date published" datetime="2015-03-24T06:31:54+00:00">{{ $article->created }}</time></span>
            <span class="byline"><span class="author vcard"><i class="fa fa-user"></i><a class="url fn n" href="http://rusiq.ru/author/rusiq/" title="{{ $article->user->name }}">{{ $article->user->name }}</a></span></span>
            <span class="comments"><a href="#respond"><i class="fa fa-comment"></i> {{ $article->comments_count }}</a></span>
            <span class="comments"><i class="fa fa-eye"></i> {{ $article->views }}</span>
        </div>
        <div class="entry-content clearfix">
            <p>{{ $article->teaser }}</p>
            <a href="{{ $article->url }}" title="{{ $article->title }}" class="more-link"><span>@lang('common.read_more')</span></a>
        </div>
    </div>
</article>
