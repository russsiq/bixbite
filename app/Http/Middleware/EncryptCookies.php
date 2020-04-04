<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * Названия файлов cookie, которые не должны быть зашифрованы.
     * @var array
     */
    protected $except = [
        \App\View\Components\ConsentCookie::ACCEPTED_NAME,

    ];
}
