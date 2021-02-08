<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class BixbiteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'articles' => \App\Models\Article::class,
            'categories' => \App\Models\Category::class,
            'comments' => \App\Models\Comment::class,
            'atachments' => \App\Models\Atachment::class,
            'tags' => \App\Models\Tag::class,
            'users' => \App\Models\User::class,
        ], true);
    }
}
