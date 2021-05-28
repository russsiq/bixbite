<?php

namespace App\Models\Relations;

use App\Models\Proxies\SettingProxy;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * @property-read EloquentCollection|Setting[] $settings Get the value of the dynamic attribute `settings`.
 * @property-read SettingProxy $setting Dynamic access to the proxied settings collection.
 */
trait CustomizableTrait
{
    /**
     * Cached in-memory settings collection.
     *
     * @var EloquentCollection|null
     */
    protected static $cachedSettings = null;

    /**
     * Boot the Customizable trait for a model.
     *
     * @return void
     */
    public static function bootCustomizable(): void
    {
        //
    }

    /**
     * Initialize the Customizable trait for an instance.
     *
     * @return void
     */
    public function initializeCustomizable(): void
    {
        //
    }

    /**
     * Get the value of the dynamic attribute `settings`.
     *
     * @return EloquentCollection
     */
    public function getSettingsAttribute(): EloquentCollection
    {
        if (is_null(static::$cachedSettings)) {
            static::$cachedSettings = Setting::settings($this->getTable());
        }

        return static::$cachedSettings;
    }

    /**
     * Dynamic access to the proxied settings collection.
     *
     * @return SettingProxy
     */
    public function getSettingAttribute(): SettingProxy
    {
        return new SettingProxy($this->settings);
    }
}
