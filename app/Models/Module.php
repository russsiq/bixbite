<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Module model.
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $icon
 * @property string $info
 * @property bool $on_mainpage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Setting[] $settings
 *
 * @method static \Database\Factories\ModuleFactory factory()
 *
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Module extends Model
{
    use HasFactory;

    /**
     * The default table associated with the model.
     *
     * @const string
     */
    public const TABLE = 'modules';

    /**
     * {@inheritDoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritDoc}
     */
    protected $attributes = [
        'name' => '',
        'title' => null,
        'icon' => 'fa fa-puzzle-piece',
        'info' => null,
        'on_mainpage' => true,
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'name' => 'string',
        'title' => 'string',
        'icon' => 'string',
        'info' => 'string',
        'on_mainpage' => 'boolean',
    ];

    /**
     * {@inheritDoc}
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

    /**
     * Get the settings for the current model instance.
     *
     * @return HasMany
     */
    public function settings(): HasMany
    {
        return $this->hasMany(
            Setting::class, // $related
            'module_name',  // $foreignKey
            'name',         // $localKey
        );
    }
}
