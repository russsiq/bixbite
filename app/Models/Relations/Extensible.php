<?php

namespace App\Models\Relations;

use App\Models\XField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait Extensible
{
    /**
     * Boot the Extensible trait for a model.
     *
     * @return void
     */
    public static function bootExtensible(): void
    {
        static::retrieved(function ($extensible) {
            $extensible->mergeFillable(
                $extensible->x_fields->pluck('name')->toArray()
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
        $this->x_fields->pluck('name', 'type')
            ->map(function (string $column, string $type) {
                if (! isset($this->casts[$column])) {
                    switch ($type) {
                        case 'integer':
                            $this->casts[$column] = 'integer';
                            break;

                        case 'boolean':
                            $this->casts[$column] = 'boolean';
                            break;

                        case 'timestamp':
                            $this->casts[$column] = 'datetime';
                            break;

                        default:
                            $this->casts[$column] = 'string';
                            break;
                    }
                }
            });
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
