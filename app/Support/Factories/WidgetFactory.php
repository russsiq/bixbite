<?php

namespace BBCMS\Support\Factories;

use BBCMS\Support\WidgetAbstract;

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
    public function make()
    {
        try {
            $args = func_get_args();
            $active = $args[1]['active'] ?? true;

            // If widget has non active status.
            if (! $active) {
                return false;
            }

            $this->widgetName = trim((string) array_shift($args));
            $this->widgetParams = (array) array_shift($args);
            $this->widget = $this->getWidget();

            return $this->getContentFromCache();
        } catch (\Exception $e) {
            return sprintf(
                trans('common.msg.error'), $e->getMessage()
            );
        }
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
     * @throws \Exception
     * @return string
     */
    protected function getWidgetPath()
    {
        $name = html_clean($this->widgetName);
        $name = str_replace(['.', '_'], ' ', $name);
        $name = str_replace(' ', '', ucwords($name));
        $name = $this->namespace . '\\' . $name . 'Widget';

        if (! is_subclass_of($name, WidgetAbstract::class)) {
            throw new \Exception(sprintf(
                'Widget class `%s` not available!', $name
            ));
        }

        return $name;
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

        return trim(preg_replace('/(\s|\r|\n)+</', '<',
            view($this->widget->template(), compact('widget'))->render()
        ));

        // Debug function if App has error:
        // `Cannot end a section without first starting one.`
        return view($this->widget->template(), compact('widget'));
    }

    /**
     * Gets content from cache or runs widget class otherwise.
     *
     * @return mixed
     */
    protected function getContentFromCache()
    {
        $cache_key = $this->widget->cacheKey();
        $cache_time = $this->widget->cacheTime();

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

        if ($validator->fails()) {
            throw new \Exception(sprintf(
                '%s: %s', $this->widgetName, $validator->errors()->first()
            ));
        }
    }

    protected function checkWidgetTemplate()
    {
        $template = $this->widget->template();

        if (! view()->exists($template)) {
            throw new \Exception(sprintf(
                'Template `%s` of widget `%s` does not exist!', $template, $this->widgetName
            ));
        }
    }
}
