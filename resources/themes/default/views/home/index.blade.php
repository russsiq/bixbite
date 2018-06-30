<div class="row c-learfix">
    <div class="col-lg-6">
        @widget('articles.featured', [
            'limit' => 4,
            'cache_time' => 1440,
            'template' => 'widgets.articles_featured__slider',
        ])
    </div>

    <div class="col-lg-6">
        @widget('articles.featured', [
            'skip' => 4,
            'limit' => 4,
            'cache_time' => 1440,
            'template' => 'widgets.articles_featured__beside_slider',
        ])
    </div>
</div>
