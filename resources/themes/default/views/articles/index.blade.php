<div id="primary">
    <div id="content" class="clearfix">
        <header class="page-header">
            <h1 class="page-title" style="border-bottom: 2px solid #289dcc;">
                @if (pageinfo('section'))
                    {{ cluster([pageinfo('section')->title, pageinfo('title')], ': ') }}
                @else
                    {{ pageinfo('title') }}
                @endif
                {{ pageinfo('page') ? '. Страница ' . pageinfo('page'): '' }}
            </h1>
        </header><!-- .page-header -->
        <div class="article-container clearfix">
            @if(pageinfo('is_category'))
                @include('components.category_info')
            @endif
            @empty ($articles->count())
                @lang('common.msg.not_found')
            @else
                @each('articles.short', $articles, 'article')
            @endempty
        </div>
        <div class="general-pagination group">{{ $articles->links('components.pagination') }}</div><!-- .pagination -->
    </div>
</div>
