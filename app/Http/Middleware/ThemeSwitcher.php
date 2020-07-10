<?php

namespace App\Http\Middleware;

// Базовые расширения PHP.
use Closure;

// Сторонние зависимости.
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 * Посредник, добавляющий расположения файлов языка и шаблонов.
 */
class ThemeSwitcher
{
    /**
     * Поле, задающее значение языка.
     * @var string
     */
    const REQUEST_INPUT_LOCALE = 'app_locale';

    /**
     * Экземпляр приложения.
     * @var Application
     */
    protected $app;

    /**
     * Текущий язык.
     * @var string
     */
    protected $locale;

    /**
     * Индикатор необходимости установки куков к ответу.
     * @var bool
     */
    protected $addHttpCookie = false;

    /**
     * Создать новый экземпляр посредника.
     * @param  Application  $app
     * @return void
     */
    public function __construct(
        Application $app
    ) {
        $this->app = $app;

        $this->setLocale(
            $this->determineLocale($this->app->request)
        );
    }

    /**
     * Установить текущий язык.
     * @param  string  $locale
     * @return void
     */
    protected function setLocale(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * Определить текущий язык.
     * @param  Request  $request
     * @return string
     */
    protected function determineLocale(Request $request): string
    {
        // Если в запросе есть поле, задающее значение языка.
        if ($locale = $request->input(self::REQUEST_INPUT_LOCALE)) {
            $this->addHttpCookie();

            return $locale;
        }

        return $request->cookie(self::REQUEST_INPUT_LOCALE) ?: $this->app->getLocale();
    }

    /**
     * Обязать задание куков.
     * @return void
     */
    protected function addHttpCookie()
    {
        $this->addHttpCookie = true;
    }

    /**
     * Обработка входящего запроса.
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->setAppLocale($this->locale)
            ->associateResources($request)
            ->reloadLangJsonPaths($this->locale);

        return tap($next($request), function ($response) use ($request) {
            if ($this->shouldAddCookie()) {
                $this->addCookieToResponse($request, $response);
            }
        });
    }

    /**
     * Установить текущий язык приложения, если он изменился.
     * @param  string  $locale
     * @return $this
     */
    protected function setAppLocale(string $locale): self
    {
        // Если язык отличается от текущего языка приожения.
        if (! $this->app->isLocale($locale)) {
            // При задании локали срабатывает событие:
            // Illuminate\Foundation\Events\LocaleUpdated
            // Библиотека `Carbon` подписана на это событие.
            $this->app->setLocale($locale);
        }

        return $this;
    }

    /**
     * Ассоциировать ресурсы в соответствии с запросом.
     * @param  Request  $request
     * @return $this
     *
     * @TODO: Для API не нужно подгружать вьюшки.
     */
    protected function associateResources(Request $request): self
    {
        $langPathes = [];
        $prefix = $request->segment(1);

        // Если это панель или запрос по API.
        if (in_array($prefix, ['panel', 'api'])) {
            // Добавляем расположение шаблонов
            // административной панели сайта.
            $this->addViewLocation(skin_path('views'));

            // Добавляем расположение общих языковых файлов
            // административной панели сайта.
            $langPathes[] = skin_path('public/lang');

            // Добавляем расположение языковых файлов текущей секции
            // административной панели сайта.
            if ('panel' === $prefix and $request->segment(2) and 'login' !== $request->segment(2)) {
                $langPathes[] = skin_path('public/lang/'.$request->segment(2));
            }

            // Если это запрос по API.
            if ('api' === $prefix and $request->segment(3) and 'auth' !== $request->segment(3)) {
                // Добавляем расположение языковых файлов текущего подраздела
                // административной панели сайта.
                $langPathes[] = skin_path('public/lang/'.$request->segment(3));
            }
        } else {
            // Добавляем расположение шаблонов темы сайта.
            $this->addViewLocation(theme_path('views'));

            // Добавляем расположение языковых файлов темы сайта.
            $langPathes[] = theme_path('public/lang');
        }

        foreach (array_filter($langPathes) as $path) {
            $this->addLangJsonPath($path);
        }

        return $this;
    }

    /**
     * Необходимо ли установить язык приложения в куки.
     * @return bool
     */
    protected function shouldAddCookie(): bool
    {
        return $this->addHttpCookie;
    }

    /**
     * Добавить язык приложения к кукам ответа.
     * @param  Request  $request
     * @param  BaseResponse  $response
     * @return BaseResponse
     */
    protected function addCookieToResponse($request, $response): BaseResponse
    {
        if ($response instanceof Responsable) {
            $response = $response->toResponse($request);
        }

        return $response->withCookie(self::REQUEST_INPUT_LOCALE, $this->locale, 2628000);
    }

    /**
     * Добавить расположение файлов шаблона.
     * @param  string  $location
     * @return void
     */
    protected function addViewLocation(string $path)
    {
        $this->app->view->addLocation($path);
    }

    /**
    * Добавить расположение JSON файлов со строками перевода.
    * @param  string  $path
    * @return void
    */
    protected function addLangJsonPath(string $path)
    {
        $this->app->translator->addJsonPath($path);
    }

    /**
     * Подгрузить ассоциированные языковые строки из JSON файлов.
     * @param  string  $locale
     * @return void
     */
    protected function reloadLangJsonPaths(string $locale)
    {
        $this->app->translator->reloadJsonPaths('*', '*', $locale);
    }
}
