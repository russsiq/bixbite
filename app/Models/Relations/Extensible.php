<?php

namespace App\Models\Relations;

// Сторонние зависимости.
use App\Models\XField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait Extensible
{
    /**
     * Получить значение динамического атрибута `x_fields`.
     * @return Collection
     */
    public function getXFieldsAttribute(): Collection
    {
        return XField::fields($this->getTable());
    }

    /**
     * Включить в выборку имена дополнительных полей.
     * @param  Builder  $builder
     * @return void
     */
    public function scopeIncludeXFieldsNames(Builder $builder): void
    {
        // $names = $this->x_fields->pluck('name', 'extensible')
        //     ->map(fn (string $field, string $table) => $table.'.'.$field)
        //     ->values();

        $table = $this->getTable();

        $names = $this->x_fields->pluck('name')
            ->map(function (string $field, int $key) use ($table) {
                return $table.'.'.$field;
            });

        $builder->addSelect($names->toArray());
    }
}
