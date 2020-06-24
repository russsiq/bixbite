<?php

namespace App\Mixins;

class StrMixin
{
    /**
     * Remove html tags and non printable chars.
     * @return callable
     *
     * @source https://blog.sergey-lysenko.ru/2012/09/php-remove-non-printable-chars.html
     */
    public function cleanHTML(): callable
    {
        return function(string $text = null): string {
            if (is_null($text)) {
                return '';
            }

            $old_text = $text;

            $text = preg_replace("/\>(\\x20|\t|\r|\n)+\</", '> <', $text);
            $text = strip_tags($text);
            $text = preg_replace('/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u', ' ', $text);
            $text = str_replace('  ', ' ', $text);
            $text = trim($text);

            return $text === $old_text ? $text : static::cleanHTML($text);
        };
    }

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
