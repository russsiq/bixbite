<?php

namespace App\Providers;

use App\Contracts\BixBiteContract;
use App\Http\View\Composers\AuthUserComposer;
use App\Http\View\Composers\CategoriesComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory as ViewFactory;

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
    public function boot(BixBiteContract $bixbite, ViewFactory $viewFactory): void
    {
        $viewFactory->addLocation(
            $bixbite->themeViewsPath()
        );

        $viewFactory->composer([
            'components.header',
        ], CategoriesComposer::class);

        $viewFactory->composer([
            'components.header',
        ], AuthUserComposer::class);
    }
}
