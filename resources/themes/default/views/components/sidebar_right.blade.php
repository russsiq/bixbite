<aside class="sidebar sidebar-right" itemscope itemtype="http://schema.org/WPSideBar">
    <x-widgets.articles-featured :parameters="[
        {{-- 'title' => 'Рекомендуемые записи', --}}
        {{-- 'is_active' => false, --}}
        {{-- 'template' => 'components.widgets.comments-latest', --}}
        {{-- 'cache_time' => 0, --}}
        {{-- 'limit' => 8, --}}
        {{-- 'skip' => 0, --}}
        {{-- 'order_by' => 'views', --}}
        {{-- 'direction' => 'desc', --}}

        {{-- 'on_mainpage' => true, --}}
        {{-- 'sub_days' => 0, --}}

        {{-- 'ids' => [6,8], --}}
        {{-- 'user_ids' => [8,51], --}}
        {{-- 'categories' => [1,3,4], --}}
        {{-- 'tags' => ['бизнес','политика'], --}}
        {{-- 'x_fields' => [
            ['articles.x_color', '=', 'желтый'],
            ['articles.x_price', '>', '200'],

        ], --}}

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

{{-- Для виджетов также доступна подгрузка по AJAX. Пример ниже. --}}
{{--
    // Создаем экземпляр AJAX провайдера.
    const axios = new Axios;

    // Отправляем AJAX запрос.
    axios.get('http://localhost/bixbite/widget/comments.latest', {
        params:{
            is_active: true
        }
    })
    .then((response) => {
        console.log(response);
    });
--}}
