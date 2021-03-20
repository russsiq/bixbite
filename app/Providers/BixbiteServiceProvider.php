<?php

namespace App\Providers;

use App\Contracts\BixBiteContract;
use App\Contracts\JsonApiContract;
use App\Support\BixBite;
use App\Support\JsonApi;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\AbstractPaginator;
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
        JsonApiContract::class => JsonApi::class,
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
        $this->app->resolving(
            LengthAwarePaginatorContract::class,
            function (LengthAwarePaginatorContract $paginator, $app) {
                /** @var AbstractPaginator $paginator */
                $paginator->onEachSide(1);

                if ($app->make(JsonApiContract::class)->isApiUrl()) {
                    $paginator->setPageName('page[number]');
                }
            }
        );

        Relation::morphMap(JsonApiContract::RESORCE_TO_MODEL_MAP, true);
    }
}
