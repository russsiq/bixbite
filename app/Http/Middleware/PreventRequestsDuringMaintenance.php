<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * Доступность разделов при включенном режиме обслуживания.
     *
     * @var array
     */
    protected $except = [
        'assistant/*',

    ];
}
