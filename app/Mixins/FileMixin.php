<?php

namespace App\Mixins;

use Illuminate\Support\Str;

class FileMixin
{
    /**
     * Отобразить размер файла в удобочитаемом формате в байтах, КБ, МБ, ГБ, ТБ.
     * @return callable
     */
    public function humanSize(): callable
    {
        return function (string $path, int $precision = 2): string {
            return Str::humanFilesize($this->size($path, $precision));
        };
    }
}
