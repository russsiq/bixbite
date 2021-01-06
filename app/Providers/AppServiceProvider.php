<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Основной поставщик служб.
 * Индивидуален для каждого проекта.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Регистрация любых служб приложения.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Загрузка любых служб приложения.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
