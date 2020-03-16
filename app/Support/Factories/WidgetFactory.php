<?php

namespace BBCMS\Support\Factories;

use Throwable;
use Exception;
use InvalidArgumentExceptions;

use BBCMS\Support\WidgetAbstract;

use Illuminate\Validation\ValidationException;

class WidgetFactory
{
    /**
     * Widget object to work with.
     *
     * @var WidgetAbstract
     */
    protected $widget;

    /**
     * The name of the widget being called.
     *
     * @var string
     */
    public $widgetName;

    /**
     * Array of widget incoming parameters.
     *
     * @var array
     */
    public $widgetParams;

    /**
     * This namespace is applied to the location of the widget folder.
     *
     * @var string
     */
    protected $namespace = '\BBCMS\Widgets';

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Generate a widget.
     *
     * @return mixed
     */
    public function make(string $name, array $params = [])
    {
        try {
            $this->widgetName = trim($name);
            $this->widgetParams = array_merge($params, [
                'expects_json' => request()->expectsJson(),
            ]);

            // If widget has non active status.
            if ($this->widgetParams['active'] ?? true) {
                $this->widget = $this->getWidget();

                return $this->getContentFromCache();
            }

            return false;
        } catch (Throwable $e) {
            return $this->widgetParams['expects_json']
                ? $this->invalidJson($e)
                : $this->invalid($e);
        }
    }

    protected function invalid(Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            $error = $exception->validator->errors()->first();
            $message = sprintf('%s: %s', $this->widgetName, $error);
        } else {
            $message = $exception->getMessage();
        }

        return sprintf(trans('common.msg.error'), $message);
    }

    protected function invalidJson(Throwable $exception)
    {
        $response['message'] = $exception->getMessage();

        if ($exception instanceof ValidationException) {
            $response['errors'] = $exception->errors();
        }

        return response()->json($response, $exception->status ?? 500);
    }

    /**
     * Set class properties and make a widget object.
     *
     * @return mixed
     */
    protected function getWidget()
    {
        $widgetClass = $this->getWidgetPath();

        return new $widgetClass($this->widgetParams);
    }

    /**
     * Get full path with name to widget class location.
     *
     * @throws Exception
     * @return string
     */
    protected function getWidgetPath()
    {
        $name = html_clean($this->widgetName);
        $name = str_replace(['.', '_'], ' ', $name);
        $name = str_replace(' ', '', ucwords($name));
        $name = $this->namespace . '\\' . $name . 'Widget';

        if (is_subclass_of($name, WidgetAbstract::class)) {
            return $name;
        }

        throw new Exception(sprintf(
            'Widget class [%s] not available!',
            $name
        ));
    }

    /**
     * Make call and get widget content.
     *
     * @return mixed Html compiled string.
     */
    protected function getContent()
    {
        $widget = (object) $this->widget->execute();
        $widget->cache_key = $this->widget->cacheKeys();

        if ($this->widgetParams['expects_json']) {
            return response()->json($widget, 200);
        }

        $view = view($this->widget->template(), compact('widget'));

        // // Debug function if App has error:
        // // `Cannot end a section without first starting one.`
        // if (config('app.debug')) {
        //     return $view;
        // }

        return trim(preg_replace('/(\s|\r|\n|\t)+</', '<', $view->render()));
    }

    /**
     * Gets content from cache or runs widget class otherwise.
     *
     * @return mixed
     */
    protected function getContentFromCache()
    {
        $cache_key = $this->widget->cacheKey();
        $cache_time = $this->widget->cacheTime() * 60;

        // Check the existence of the cache.
        if (! cache()->has($cache_key)) {
            // If cache is empty and validation is fails.
            $this->checkWidgetParams();
            // If cache is empty and template file does not exists.
            $this->checkWidgetTemplate();
        }

        return cache()->remember($cache_key, $cache_time, function () {
            return $this->getContent();
        });
    }

    protected function checkWidgetParams()
    {
        $validator = $this->widget->validator();

        return $validator->validate();
    }

    protected function checkWidgetTemplate()
    {
        $template = $this->widget->template();

        if (! view()->exists($template)) {
            throw new Exception(sprintf(
                'Template [%s] of widget [%s] does not exist!',
                $template,
                $this->widgetName
            ));
        }
    }
}
