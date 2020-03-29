<?php

namespace App\Http\Controllers;

// Сторонние зависимости.
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Абстрактный класс базового контроллера.
 */
abstract class BaseController extends Controller
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * Создать HTTP-ответ.
     * @param  string  $template
     * @param  array  $vars
     * @return mixed
     */
    protected function makeResponse(string $template, array $vars = [])
    {
        if (request()->expectsJson()) {
            return $this->jsonOutput($vars);
        }

        return $this->renderOutput($template, $vars);
    }

    /**
     * Выполнить редирект по указанному пути.
     * @param  bool  $status
     * @param  string|array  $route
     * @param  string  $message
     * @return mixed
     */
    protected function makeRedirect(bool $status, $route, string $message)
    {
        if (request()->expectsJson()) {
            return $this->jsonOutput(compact('status', 'route', 'message'));
        }

        if (is_array($route)) {
            $url = route(array_shift($route), $route);
        } elseif (strpos($route, '/') === false) {
            if (\Route::has($route)) {
                $url = route($route);
            } else {
                abort(404, "Route named $route does not exist.");
            }
        }

        return $status
            ? redirect()->to($url ?? $route)->withStatus($message)
            : redirect()->to($url ?? $route)->withErrors($message);
    }

    /**
     * Отправить JSON-ответ.
     * @param  array  $vars
     * @return JsonResponse
     */
    protected function jsonOutput(array $vars = []): JsonResponse
    {
        $vars = array_merge([
            'status' => true,
        ], $vars);

        return response()->json($vars, 200);
    }

    /**
     * Получить HTML-строковое представление ответа.
     * @param  string  $template
     * @param  array  $vars
     * @return Renderable
     */
    protected function renderOutput(string $template, array $vars = []): Renderable
    {
        $tpl = $this->template.'.'.$template;

        if (view()->exists($tpl)) {
            return view($tpl, $vars);
        }

        abort(404, "View named [$tpl] not exists.");
    }
}
