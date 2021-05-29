<?php

namespace App\Models\Relations;

use App\Models\Contracts\ExtensibleContract;
use App\Models\XField;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @property-read EloquentCollection|XField[] $x_fields Get the value of the dynamic attribute `x_fields`.
 * @method static static|EloquentBuilder|QueryBuilder includeExtensibleAttributes() Include names of extra fields in the query.
 */
trait ExtensibleTrait
{
    /**
     * Cached in-memory extra fields collection.
     *
     * @var EloquentCollection|null
     */
    protected static $cachedExtraFields = null;

    /**
     * The aditional attributes that are mass assignable.
     *
     * @var string[]
     */
    protected static $extraFieldsFillable = [];

    /**
     * The aditional attributes that should be cast.
     *
     * @var array
     */
    protected static $extraFieldsCasts = [];

    /**
     * The built-in cast types supported by Extra Fields.
     *
     * @var array
     */
    protected static $extraFieldsCastMap = [
        'default' => 'string',
        'integer' => 'integer',
        'boolean' => 'boolean',
        'timestamp' => 'datetime',
    ];

    /**
     * Boot the Extensible trait for a model.
     *
     * @return void
     */
    public static function bootExtensibleTrait(): void
    {
        static::registerModelEvent('booted', function (ExtensibleContract $extensible) {
            /** @var EloquentCollection $x_fields */
            $x_fields = $extensible->x_fields;

            static::$extraFieldsFillable = $x_fields->pluck('name')->toArray();

            static::$extraFieldsCasts = $x_fields->pluck('name', 'type')
                ->reject(
                    fn (string $name) => $extensible->hasCast($name)
                )
                ->mapWithKeys(
                    fn (string $name, string $type) => [
                        $name => static::$extraFieldsCastMap[$type]
                              ?? static::$extraFieldsCastMap['default']
                    ]
                )
                ->toArray();
        });
    }

    /**
     * Initialize the Extensible trait for an instance.
     *
     * @return void
     */
    public function initializeExtensibleTrait(): void
    {
        if (! empty(static::$extraFieldsFillable)) {
            $this->mergeFillable(static::$extraFieldsFillable);
        }

        if (! empty(static::$extraFieldsCasts)) {
            $this->mergeCasts(static::$extraFieldsCasts);
        }
    }

    /**
     * Get the value of the dynamic attribute `x_fields`.
     *
     * @return EloquentCollection
     */
    public function getXFieldsAttribute(): EloquentCollection
    {
        if (is_null(static::$cachedExtraFields)) {
            static::$cachedExtraFields = XField::fields($this->getTable());
        }

        return static::$cachedExtraFields;
    }

    /**
     * Include names of extra fields in the query.
     *
     * @param  EloquentBuilder  $builder
     * @return EloquentBuilder
     */
    public function scopeIncludeExtensibleAttributes(EloquentBuilder $builder): EloquentBuilder
    {
        return $builder->addSelect(
            $this->x_fields->pluck('name')
                ->map(fn (string $column) => $this->qualifyColumn($column))
                ->toArray()
        );
    }
}
