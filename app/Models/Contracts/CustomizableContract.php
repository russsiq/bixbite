<?php

namespace App\Models\Contracts;

use App\Models\Proxies\SettingProxy;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface CustomizableContract
{
    public function getSettingsAttribute(): EloquentCollection;

    public function getSettingAttribute(): SettingProxy;
}
