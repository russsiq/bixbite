<?php

namespace BBCMS\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;

class CheckForMaintenanceMode extends Middleware
{
    /**
     * Доступность разделов при включенном режиме обслуживания.
     * @var array
     */
    protected $except = [
        'assistant/*',

    ];
}
