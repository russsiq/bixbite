<?php

namespace App\Models\Relations;

use App\Models\XField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait Extensible
{
    /**
     * The built-in cast types supported by Extra Fields.
     *
     * @var array
     */
    protected $extraFieldsCastMap = [
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
    public static function bootExtensible(): void
    {
        // Extra fields can only be set for instances of existing models in the database.
        static::retrieved(function ($extensible) {
            /** @var Collection */
            $x_fields = $extensible->x_fields;

            $extensible->mergeFillable(
                $x_fields->pluck('name')->toArray()
            );

            $extensible->mergeCasts(
                $x_fields->pluck('name', 'type')
                    ->reject(fn (string $name) => isset($extensible->casts[$name]))
                    ->mapWithKeys(
                        fn (string $name, string $type) => [
                            $name => $extensible->extraFieldsCastMap[$type]
                                ?? $extensible->extraFieldsCastMap['default']
                        ]
                    )
                    ->toArray()
            );
        });
    }

    /**
     * Initialize the Extensible trait for an instance.
     *
     * @return void
     */
    public function initializeExtensible(): void
    {
        //
    }

    /**
     * Get the value of the dynamic attribute `x_fields`.
     *
     * @return Collection
     */
    public function getXFieldsAttribute(): Collection
    {
        return XField::fields($this->getTable());
    }

    /**
     * Include names of extra fields in the query.
     *
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeIncludeXFieldsNames(Builder $builder): Builder
    {
        return $builder->addSelect(
            $this->x_fields->pluck('name')
                ->map(fn (string $column) => $this->qualifyColumn($column))
                ->toArray()
        );
    }
}
