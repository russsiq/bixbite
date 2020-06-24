<?php

namespace App\Mixins;

use voku\helper\ASCII;

class StrMixin
{
    /**
     * Удаление HTML-тегов, непечатаемых символов.
     * @return callable
     *
     * @NB Фильтры с флагами `FILTER_FLAG_STRIP_LOW`, `FILTER_FLAG_STRIP_HIGH` вырезают кирилицу.
     * @info https://blog.sergey-lysenko.ru/2012/09/php-remove-non-printable-chars.html
     * @info https://www.php.net/manual/ru/regexp.reference.unicode.php
     * @info http://fkn.ktu10.com/?q=node/7082
     */
    public function cleanHTML(): callable
    {
        return function(string $text = null): string {
            if (is_null($text)) {
                return '';
            }

            // Заменяем все пробелы, табуляции, переносы м/д тегами на пробелы.
            $text = preg_replace("/\>(\\x20|\t|\r|\n)+\</", '> <', $text);

            // Удаляем HTML-теги из строки.
            $text = strip_tags($text);

            // Удаляем все не UTF-8 символы.
            $text = ASCII::clean($text);

            // Замеяем все кроме буквы, числа, пунктуацию, пробельный разделитель.
            $text = preg_replace('/([^\pL\pN\pP\p{Zs}])/u', ' ', $text);

            // Убираем двойные пробелы.
            $text = str_replace('  ', ' ', $text);

            return trim($text);
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
