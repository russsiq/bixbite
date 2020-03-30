<aside class="sidebar sidebar-right" itemscope itemtype="http://schema.org/WPSideBar">
    <x-widgets.articles-featured :parameters="[
        'is_active' => true,
        'limit' => 5,
        {{-- 'cache_time' => 0, --}}
        {{-- 'sub_day' => 1, --}}
        {{-- 'order_by' => 'title', --}}
        {{-- 'direction' => 'asc', --}}
        {{-- 'id' => [6,8], --}}
        {{-- 'user_id' => [1,2], --}}
        {{-- 'categories' => [1,3,4], --}}
        {{-- 'tags' => ['бизнес','политика'], --}}

    ]" />

    <x-widgets.comments-latest :parameters="[
        {{-- 'title' => 'Обсуждения', --}}
        {{-- 'is_active' => false, --}}
        {{-- 'template' => 'components.widgets.comments-latest', --}}
        {{-- 'cache_time' => 0, --}}
        {{-- 'limit' => 8, --}}
        {{-- 'content_length' => 150, --}}
        {{-- 'relation' => 'articles', --}}

    ]" />

    <x-widgets.tags-cloud :parameters="[
        {{-- 'title' => 'Облако меток', --}}
        {{-- 'is_active' => false, --}}
        {{-- 'template' => 'components.widgets.tags-cloud', --}}
        {{-- 'cache_time' => 0, --}}
        {{-- 'limit' => 8, --}}
        {{-- 'relation' => 'articles', --}}
        {{-- 'order_by' => 'articles_count', --}}
        {{-- 'direction' => 'desc', --}}

    ]" />

    <x-widgets.articles-archives :parameters="[
        {{-- 'title' => 'Архив записей', --}}
        {{-- 'is_active' => false, --}}
        {{-- 'template' => 'components.widgets.articles-archives', --}}
        {{-- 'cache_time' => 0, --}}
        {{-- 'limit' => 12, --}}
        {{-- 'has_count' => true,  --}}

    ]" />
</aside>
