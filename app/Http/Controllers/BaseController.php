<?php

namespace BBCMS\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Make response description.
     *
     * @param  string $template [description]
     * @param  array  $vars     [description]
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
     * Create a new redirect response to the given path.
     *
     * @param  bool         $status  Specifies a redirect with an error or notification.
     * @param  string|array $route   The name of the route to redirect.
     * @param  string       $message Error or notification to display.
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

    protected function jsonOutput(array $vars = [])
    {
        $vars = array_merge([
            'status' => true,
        ], $vars);

        return response()->json($vars, 200);
    }

    /**
     * Render output to html string.
     *
     * @param  string $template
     * @param  array  $vars
     * @return mixed
     */
    protected function renderOutput(string $template, array $vars = [])
    {
        $tpl = $this->template . '.'. $template;

        if (view()->exists($tpl)) {
            return view($tpl, $vars)->render();
        }

        abort(404, "View named [$tpl] not exists.");
    }
}
