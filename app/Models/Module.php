<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

/**
 * Module model.
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $title
 * @property-read string $icon
 * @property-read string $info
 * @property-read bool $on_mainpage
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 */
class Module extends Model
{
    use HasFactory;

    public const TABLE = 'modules';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'name' => '',
        'title' => null,
        'icon' => 'fa fa-puzzle-piece',
        'info' => null,
        'on_mainpage' => true,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'title' => 'string',
        'icon' => 'string',
        'info' => 'string',
        'on_mainpage' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'title',
        'icon',
        'info',
        'on_mainpage',
    ];

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
        Lang::addJsonPath(dashboard_path('lang'.DS.$module_name));
    }

    public static function loadView(string $module_name)
    {
        // \View::addLocation('path/to/theme/views');
    }
}
