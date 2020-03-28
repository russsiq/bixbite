<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login as EventLogin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuccessfulLogin
{
    /**
     * Создать слушателя события.
     */
    public function __construct() {}

    /**
     * Обработать событие.
     * @param  EventLogin  $event
     * @return void
     */
    public function handle(EventLogin $event): void
    {
        $event->user->update([
            'last_ip' => request()->ip(),
            'logined_at' => date('Y-m-d H:i:s'),

        ]);
    }
}
