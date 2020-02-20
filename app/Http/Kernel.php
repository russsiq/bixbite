<?php

namespace BBCMS\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Глобальный стек HTTP посредников приложения.
     * Эти посредники запускаются во время каждого запроса к вашему приложению.
     * @var array
     */
    protected $middleware = [
        \BBCMS\Http\Middleware\ThemeSwitcher::class,
        // \BBCMS\Http\Middleware\AccessToLockedSite::class,
        \BBCMS\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \BBCMS\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \BBCMS\Http\Middleware\TrustProxies::class,
    ];

    /**
     * Группы посредников маршрутов приложения.
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \BBCMS\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \BBCMS\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            // BBCMS Middleware
            \BBCMS\Http\Middleware\LastUserActivity::class,
        ],

        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Посредники маршрутов приложения.
     * Эти посредники могут быть групповыми или использоваться по отдельности.
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \BBCMS\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'debugbar.disable' => \BBCMS\Http\Middleware\DebugbarDisable::class,
        'guest' => \BBCMS\Http\Middleware\RedirectIfAuthenticated::class,
        'own_profile' => \BBCMS\Http\Middleware\OwnProfile::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'role' => \BBCMS\Http\Middleware\CheckRole::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];


    /**
     * Список посредников, отсортированный по приоритетности.
     * Заставит неглобальных посредников всегда быть в заданном порядке.
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \BBCMS\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
