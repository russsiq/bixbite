<?php

namespace BBCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;

class CacheFile extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cachefile';
    }
}
