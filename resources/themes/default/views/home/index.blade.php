<div class="row c-learfix">
    <div class="col-lg-6">
        <x-widgets.articles-featured :parameters="[
            'limit' => 4,
            'cache_time' => 1440,
            'template' => 'widgets.articles_featured__slider',
        ]"></x-comments-latest-widget>
    </div>

    <div class="col-lg-6">
        <x-widgets.articles-featured :parameters="[
            'skip' => 4,
            'limit' => 4,
            'cache_time' => 1440,
            'template' => 'widgets.articles_featured__beside_slider',
        ]"></x-comments-latest-widget>
    </div>
</div>
