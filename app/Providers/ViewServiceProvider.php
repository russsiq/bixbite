<?php

namespace App\Providers;

use App\Http\View\Composers\AuthUserComposer;
use App\Http\View\Composers\CategoriesComposer;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @param  ViewFactory  $viewFactory
     * @return void
     */
    public function boot(ViewFactory $viewFactory): void
    {
        $viewFactory->composer([
            'components.navbar',
        ], CategoriesComposer::class);

        $viewFactory->composer([
            'components.navbar',
        ], AuthUserComposer::class);
    }
}
