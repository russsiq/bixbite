<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    /**
     * Получить список встроенных примитивных типизаторов.
     *
     * @return array
     */
    public function primitiveCastTypes(): array
    {
        return static::$primitiveCastTypes;
    }

    /**
     * Определить, что переданный тип относится к примитивной типизации.
     *
     * @param  string $castType
     * @return bool
     */
    public function isPrimitiveCastTypes(string $castType): bool
    {
        return in_array($castType, primitiveCastTypes());
    }
}
