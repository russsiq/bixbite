<div id="primary">
    <div id="content" class="clearfix">
        <header class="page-header">
            <h1 class="page-title" style="border-bottom: 2px solid #289dcc;">
                {{ pageinfo('title') }}
                {{ pageinfo('page') ? '. Страница ' . pageinfo('page'): '' }}
            </h1>
        </header><!-- .page-header -->
        <div class="article-container clearfix">
            @empty ($tags->count())
                @lang('common.msg.not_found')
            @else
                {{ wrap_attr($tags, '<a href="%url" class="mb-1 btn btn-sm btn-outline-dark" rel="tag">%title</a>', ' ') }}
            @endforelse
        </div>
        <div class="general-pagination group">{{ $tags->links('components.pagination') }}</div><!-- .pagination -->
    </div>
</div>
