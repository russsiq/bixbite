<article class="full-post clearfix">
    @can ('admin.articles.update', $article)
        <a href="{{ route('admin.articles.edit', $article) }}" title="@lang('editnews')" class="pull-right"><i class="fa fa-edit text-danger"></i></a>
    @endcan

    <header class="page-header">
        <h2 class="section-title">{{ $article->title }}</h2>
    </header><!-- .page-header -->

    {!! $article->content !!}

    <hr class="text-danger" />
    <p><i class="fa fa-folder-open-o"></i> {{ wrap_attr($article->categories, '<span class="cat-links"><a href="%url" rel="category">%title</a></span>') }}</p>
</article>
