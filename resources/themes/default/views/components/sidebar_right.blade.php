<aside class="sidebar sidebar-right" itemscope itemtype="http://schema.org/WPSideBar">
    <x-widgets.articles-featured :parameters="[
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
    ]"></x-comments-latest-widget>

    @if (setting('comments.widget_used', true))
        <x-widgets.comments-latest :parameters="[
            // 'cache_time' => 0,
        ]"></x-comments-latest-widget>
    @endif

    <x-widgets.tags-cloud />
    <x-widgets.articles-archives />
</aside>

{{-- Для виджетов также доступна подгрузка по AJAX. Пример ниже. --}}
{{--
    // Создаем экземпляр AJAX провайдера.
    const axios = new Axios;

    // Отправляем AJAX запрос.
    axios.get('http://localhost/bixbite/widget/comments.latest', {
        params:{
            active: true
        }
    })
    .then((response) => {
        console.log(response);
    });
--}}
