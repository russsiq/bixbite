<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * Имена файлов Cookies, которые не должны быть зашифрованы.
     *
     * @var array
     */
    protected $except = [
        \App\View\Components\ConsentCookie::ACCEPTED_NAME,

    ];
}
