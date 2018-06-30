<?php

namespace BBCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;

class PageInfo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pageinfo';
    }
}
