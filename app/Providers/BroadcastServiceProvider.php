<?php

namespace BBCMS\Providers;

// Зарегистрированные фасады приложения.
use Broadcast;

// Сторонние зависимости.
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Загрузка любых служб приложения.
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
