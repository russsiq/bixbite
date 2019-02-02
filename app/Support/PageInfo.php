<?php

namespace BBCMS\Support;

use BBCMS\Exceptions\MethodNotExist;
use BBCMS\Exceptions\MethodNotAvailable;

/**
* pageinfo([...])
* pageinfo()->make([...])
* \PageInfo::make([...])
*/

class PageInfo
{
    protected $attributes = [];

    public function __construct()
    {
        $this->set('locale', str_replace('_', '-', app_locale()));
        $this->set('csrf_token', csrf_token());
        $this->set('page', request('page') ?? null);
        $this->set('app_name', setting('system.app_name', 'BixBite'));
    }

    public function make(array $data)
    {
        foreach (array_filter($data) as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function makeTitles()
    {
        if ($this->get('onHomePage') and $title = $this->get('title')) {
            return $title;
        }

        // Keys are shown for visual perception.
        $titles = [
            'header' => $this->get('app_name'),
            'section' => $this->get('section')->title ?? null,
            'title' => $this->get('title') ?? null,
        ];
        return cluster(
            setting('system.meta_title_reverse', false) ? array_reverse($titles) : $titles,
            setting('system.meta_title_delimiter', ' — ')
        );
    }

    protected function set(string $key, $value = null)
    {
        if ($key = trim($key) and ! is_null($value)) {
            $this->attributes[$key] = $value;
        }
    }

    public function get(string $key = null)
    {
        return is_null($key) ? $this->attributes : $this->getAttribute($key);
    }

    protected function getAttribute(string $key)
    {
        $value = $this->attributes[$key] ?? null;

        return is_array($value) ? (object) $value : $value;
    }

    protected function getSubAttribute(string $key, array $subKey)
    {
        if (empty($subKey)) {
            return is_array($value = $this->get($key)) ? (object) $value : $value;
        } elseif (is_string($subKey[0])) {
            return $this->get($key)[$subKey[0]];
        }

        throw new MethodNotExist(sprintf(
            'Call to undefined method %s::%s!', static::class, $method
        ));
    }

    public function has(string $key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Set the attributes if the condition is truthy.
     *
     * @param  bool  $condition
     * @param  array  $data
     * @return mixed
     */
    public function when($condition, array $data)
    {
        if ($this->has($condition)) {
            return $this->make($data);
        }
    }

    /**
     * Set the attributes if the condition is not truthy.
     *
     * @param  bool  $condition
     * @param  array  $data
     * @return mixed
     */
    public function unless($condition, array $data)
    {
        if (! $this->has($condition)) {
            return $this->make($data);
        }
    }

    /**
     * Dynamically retrieve attributes.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        throw new MethodNotAvailable(sprintf(
            '%s::%s not available!', static::class, '__set'
        ));
    }

    public function __call($method, $arguments)
    {
        if ($this->has($method)) {
            return $this->getSubAttribute($method, $arguments);
        }

        throw new MethodNotExist(sprintf(
            'Call to undefined method %s::%s!', static::class, $method
        ));
    }
}
