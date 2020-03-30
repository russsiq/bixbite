<div class="row c-learfix">
    {{--
        Дополнительная информация по виджету, расположена в шаблоне
        `\resources\themes\default\views\components\sidebar_right.blade.php`
    --}}
    <x-widgets.articles-featured :parameters="[
        'limit' => 5,
        'cache_time' => 1440,
        'template' => 'components.widgets.articles-promo',

    ]" />
</div>
