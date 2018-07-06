<section class="archive_page">
    <div class="archive_page__inner">
        <header class="archive_page__header">
            <h2 class="archive_page__title">{{ pageinfo('title') }}</h2>
        </header>

        <div class="archive_page__content">
            @empty ($users->count())
                @lang('common.msg.not_found')
            @else
                {{ wrap_attr($users, '<a href="%url" class="my-1 btn btn-sm btn-outline-dark" rel="tag">%name</a>', ' ') }}
                {{ $users->links('components.pagination') }}
            @endforelse
        </div>
    </div>
</section>
