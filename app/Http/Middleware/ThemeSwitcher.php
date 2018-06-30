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

class ThemeSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        App::setLocale(app_locale()); // Lang::setLocale($locale); // $request->setDefaultLocale($locale);
        Carbon::setLocale(app_locale());

        if (in_array($request->segment(1), ['admin', 'installer'])) { // trim($request->route()->getPrefix(), '/')
            // Load skin view.
            View::addLocation(skin_path('views'));
            // Load skin common lang.
            Lang::addJsonPath(skin_path('lang'));
            // Load skin section lang.
            Lang::addJsonPath(skin_path('lang' . DS . ($request->segment(2) ?? 'install')));
        } else {
            // Load theme view.
            View::addLocation(theme_path('views'));
            // Load theme lang.
            Lang::addJsonPath(theme_path('lang'));
        }

        return $next($request);
    }
}
