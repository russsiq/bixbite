<?php

namespace App\Providers;

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
            // 'categories' => \App\Models\Category::class,
            // 'comments' => \App\Models\Comment::class,
            // 'files' => \App\Models\File::class,
            // 'notes' => \App\Models\Note::class,
            // 'tags' => \App\Models\Tag::class,
            'users' => \App\Models\User::class,
        ], true);
    }
}
