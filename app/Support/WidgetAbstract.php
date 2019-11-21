<?php

namespace BBCMS\Support;

use BBCMS\Models\Privilege;
use Validator;

abstract class WidgetAbstract
{

    /**
     * All parameters of widget: from settings file and incoming when widget is called.
     * @var array
     */
    protected $params = [];

    /**
     * The parameters that should be cast to native types.
     * @var array
     */
    protected $casts = [];

    /**
     * Widget cache lifetime.
     * @var integer
     */
    protected $cacheTime = 0;

    /**
     * Widget cache unique key.
     * @var string
     */
    protected $cacheKey = null;

    /**
     * Template by default.
     * @var string
     */
    protected $template;

    public function __construct(array $params = [])
    {
        $params = array_merge(
            $this->default(), $params
        );

        foreach ($params as $key => $value) {
            $this->params[$key] = $this->castParam($key, $value);
        }
    }

    /**
     * Get default data for the widget.
     * @return array
     */
    abstract protected function default();

    /**
     * Get the validation rules that execute to the widget.
     * @return array
     */
    abstract protected function rules();

    /**
     * Generate a widget.
     * @return array
     */
    abstract public function execute();

    /**
     * Get a template path.
     * @return string
     */
    public function template()
    {
        return $this->params['template'] ?? $this->template;
    }

    /**
     * Get widget cache time or false if it's not meant to be cached.
     * @return integer
     */
    public function cacheTime()
    {
        return $this->params['cache_time'] ?? $this->cacheTime;
    }

    /**
     * Get a unique key of the widget for caching.
     * @return string
     */
    public function cacheKey()
    {
        $this->cacheKey = $this->cacheKey ??
            $this->generateCacheKey(
                auth()->guest() ? 'guest' : auth()->user()->role
            );

        return $this->cacheKey;
    }


    /**
     * Get all keys of the widget for clearing cache.
     * @return string
     */
    public function cacheKeys()
    {
        $keys = [];
        $roles = array_merge(Privilege::getModel()->roles(), ['guest']);

        foreach ($roles as $role) {
            $keys[] = $this->generateCacheKey($role);
        }

        return implode('|', $keys);
    }

    /**
     * Generate a unique cache key depending on the input parameters.
     * @param  string $role
     * @return string
     */
    protected function generateCacheKey(string $role)
    {
        return md5(serialize(array_merge($this->params, [
            'widget' => get_class($this),
            'app_theme' => app_theme(),
            'app_locale' => app_locale(),
            'role' => $role,
        ])));
    }

    /**
     * Validate incoming parameters.
     * @return mixed
     */
    public function validator()
    {
        return Validator::make(
            $this->params, $this->rules(),
            $this->messages(), $this->attributes()
        );
    }

    /**
     * Get custom messages for validator errors.
     * @return array
     */
    protected function messages()
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     * @return array
     */
    protected function attributes()
    {
        return [];
    }

    /**
     * Cast an param to a native PHP type.
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function castParam($key, $value)
    {
        if (is_null($value) or ! $this->hasCast($key)) {
            return $value;
        }

        switch ($this->getCastType($key)) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'array':
                return (array) $value;
            case 'collection':
                return collect((array) $value);
            default:
                return $value;
        }
    }

    /**
     * Determine whether an param should be cast to a native type.
     * @param  string  $key
     * @return bool
     */
    protected function hasCast($key)
    {
        return array_key_exists($key, $this->casts);
    }

    /**
     * Get the type of cast for a widget param.
     * @param  string  $key
     * @return string
     */
    protected function getCastType($key)
    {
        return trim(strtolower($this->casts[$key]));
    }
}
