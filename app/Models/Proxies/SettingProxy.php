<?php

namespace App\Models\Proxies;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingProxy
{
    /**
     * The collection being operated on.
     *
     * @var Collection
     */
    protected $collection;

    /**
     * Create a new proxy instance.
     *
     * @param  Collection  $collection
     * @return void
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Proxy accessing an attribute onto the collection items.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getSettingValue($key);
    }

    /**
     * Proxy a method call onto the collection items.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters = [])
    {
        return $this->getSettingValue($method, $parameters[0] ?? null);
    }

    /**
     * Retrieve `value` attribute on the Setting model.
     *
     * @param  string  $name
     * @param  mixed  $default
     * @return mixed
     */
    protected function getSettingValue(string $name, mixed $default = null): mixed
    {
        $setting = $this->getSettingByName($name);

        return is_null($setting) ? value($default) : $setting->value;
    }

    /**
     * Get setting instance by name.
     *
     * @param string $name
     * @return Setting|null
     */
    protected function getSettingByName(string $name): ?Setting
    {
        return $this->collection->first(fn (Setting $setting) => $name === $setting->name, null);
    }
}
