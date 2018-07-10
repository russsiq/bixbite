<aside class="sidebar sidebar-right" itemscope itemtype="http://schema.org/WPSideBar">
    @widget(' articles.featured', [
        'active' => true,
        'limit' => 5,
        // 'cache_time' => 0,
        // 'sub_day' => 1,
        // 'order_by' => 'title',
        // 'direction' => 'asc',
        // 'id' => [6,8],
        // 'user_id' => [1,2],
        // 'categories' => [1,3,4],
        // 'tags' => ['бизнес','политика'],
    ])

    @if (setting('comments.widget_used', true))
        @widget('comments.latest', [
            // 'cache_time' => 0,
        ])
    @endif

    @widget('tags.cloud')
    @widget('articles.archives')
</aside>
