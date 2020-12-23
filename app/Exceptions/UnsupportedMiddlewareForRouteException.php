<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Исключение, выбрасываемое,
 * когда применяемый посредник
 * не предназначен для выбранного маршрута.
 */
class UnsupportedMiddlewareForRouteException extends RuntimeException
{
    /**
     * Создать новый экземпляр исключения.
     *
     * @param  object  $middleware
     * @param  string  $route
     * @return self
     */
    public static function make(string $middleware, string $route): self
    {
        return new self(sprintf(
            'This middleware [%s] is not supported by this route [%s].',
            $middleware,
            $route
        ));
    }
}
