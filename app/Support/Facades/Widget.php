<?php

namespace BBCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Widget extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'widget';
    }
}
