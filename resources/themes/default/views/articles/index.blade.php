<section class="archive_page">
    <div class="archive_page__inner">
        <header class="archive_page__header">
            <h2 class="archive_page__title">
                {{ pageinfo('title') }}

                @if(pageinfo('is_category'))
                    @can ('update', $category)
                        <sup><a href="{{ $category->editPage }}"><i class="fa fa-edit"></i></a></sup>
                    @endcan
                @endif
            </h2>

            {{--
                Если текущая страница - это первая страница категории,
                то подключаем секцию с информацией о категории.
            --}}
            @if(pageinfo('is_category') and ! pageinfo('page'))
                @include('components.partials.category_info')
            @endif

            {{--
                Если текущая страница - это страница поиска,
                то всегда подключаем секцию с формой поиска.
            --}}
            @if(pageinfo('is_search'))
                @include('components.partials.search_form')
            @endif
        </header>

        @empty ($articles->count())
            @lang('common.msg.not_found')
        @else
            @each('articles.short', $articles, 'article')
            {{ $articles->links('components.pagination') }}
        @endempty
    </div>
</section>