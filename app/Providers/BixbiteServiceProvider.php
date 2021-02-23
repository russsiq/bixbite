<?php

namespace App\Providers;

use App\Contracts\BixBiteContract;
use App\Support\BixBite;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class BixbiteServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        BixBiteContract::class => BixBite::class,
    ];

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
        $this->app->resolving(PaginatorContract::class, function (PaginatorContract $paginator, $app) {
            $paginator->onEachSide(1);
        });

        Relation::morphMap([
            'articles' => \App\Models\Article::class,
            'atachments' => \App\Models\Atachment::class,
            'comments' => \App\Models\Comment::class,
            'categories' => \App\Models\Category::class,
            'tags' => \App\Models\Tag::class,
            'users' => \App\Models\User::class,
        ], true);
    }
}
