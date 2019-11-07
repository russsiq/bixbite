<?php

// @@@@@@@@@@@@@NEED REoRGANIZE
//
// При сохранении настроек нужно обновлять `app_locale` и `app_theme`,
// находящиеся в `session('')`
// При сохранении настроек нужно обновлять `lang` и `theme`,
// находящиеся в `session('')`

namespace BBCMS\Http\Middleware;

use App;
use View;
use Lang;
use Closure;
use Carbon\Carbon;

use BBCMS\Models\Module;

/**
 * НУЖНО НАВЕСТИ ПОРЯДОК В ЭТОМ ПОСРЕДНИКЕ.
 */
class ThemeSwitcher
{
    /**
     * Обработка входящего запроса.
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = app_locale();

        App::setLocale($locale); // Lang::setLocale($locale); // $request->setDefaultLocale($locale);
        Carbon::setLocale($locale);

        if (in_array($request->segment(1), ['admin', 'panel', 'installer'])) { // trim($request->route()->getPrefix(), '/')
            // Добавляем расположение шаблонов
            // административной панели сайта.
            View::addLocation(skin_path('views'));

            // Добавляем расположение общих языковых файлов
            // административной панели сайта.
            Lang::addJsonPath(skin_path('lang'));

            // Добавляем расположение языковых файлов текущего подраздела
            // административной панели сайта.
            Lang::addJsonPath(skin_path('lang'.DS.($request->segment(2) ?? 'install')));
        } else {
            // Добавляем расположение шаблонов темы сайта.
            View::addLocation(theme_path('views'));

            // Добавляем расположение языковых файлов темы сайта.
            Lang::addJsonPath(theme_path('lang'));
        }

        return $next($request);
    }
}
