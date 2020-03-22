<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class PageInfo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pageinfo';
    }
}
