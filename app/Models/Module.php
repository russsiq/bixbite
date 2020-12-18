<?php

namespace App\Models;

use App\Models\{BaseModel, Setting};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends BaseModel
{
    use HasFactory;

    protected $table = 'modules';

    /**
    * Get the route key for the model.
    *
    * @return string
    */
    public function getRouteKeyName()
    {
        return 'name';
    }

    public function settings()
    {
        return $this->hasMany(Setting::class, 'module_name', 'name');
    }

    public static function loadLang(string $module_name)
    {
        \Lang::addJsonPath(skin_path('lang' . DS . $module_name));
    }

    public static function loadView(string $module_name)
    {
        // \View::addLocation('path/to/theme/views');
    }
}
