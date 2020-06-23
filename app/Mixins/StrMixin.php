<?php

namespace App\Mixins;

class StrMixin
{
    /**
     * Отобразить размер файла в удобочитаемом формате в байтах, КБ, МБ, ГБ, ТБ.
     * @return callable
     */
    public function humanFilesize(): callable
    {
        return function(int $size, int $precision = 2): string {
            $suffixes = [
                trans('common.bytes'),
                trans('common.KB'),
                trans('common.MB'),
                trans('common.GB'),
                trans('common.TB'),

            ];

            for ($i = 0; $size > 1024; $i++) {
                $size /= 1024;
            }

            return round($size, $precision).' '.$suffixes[$i];
        };
    }
}
