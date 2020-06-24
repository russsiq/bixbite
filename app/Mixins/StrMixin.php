<?php

namespace App\Mixins;

use voku\helper\ASCII;

class StrMixin
{
    /**
     * Удалить HTML-теги и непечатаемые символы.
     * @return callable
     *
     * @NB Фильтры с флагами `FILTER_FLAG_STRIP_LOW`, `FILTER_FLAG_STRIP_HIGH` вырезают кирилицу.
     * @info https://blog.sergey-lysenko.ru/2012/09/php-remove-non-printable-chars.html
     * @info https://www.php.net/manual/ru/regexp.reference.unicode.php
     * @info http://fkn.ktu10.com/?q=node/7082
     */
    public function cleanHTML(): callable
    {
        return function (string $text = null): string {
            if (is_null($text)) {
                return '';
            }

            // Заменяем все пробелы, табуляции, переносы м/д тегами на пробелы.
            $text = preg_replace("/\>(\\x20|\t|\r|\n)+\</", '> <', $text);

            // Удаляем HTML-теги из строки.
            $text = strip_tags($text);

            // Удаляем все не UTF-8 символы.
            $text = ASCII::clean($text, true, false, false, true);

            // Замеяем все кроме буквы, числа, пунктуацию, пробельный разделитель.
            $text = preg_replace('/([^\pL\pN\pP\p{Zs}])/u', ' ', $text);

            // Преобразовываем оставшиеся HTML-сущности.
            $text = static::secureHtml($text);

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
        return function (int $size, int $precision = 2): string {
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

    /**
     * Подсчет времени, за которое текст может быть прочитан.
     * @return callable
     */
    public function readingTime(): callable
    {
        return function (string $text): string {
            $word_count = str_word_count(strip_tags($text));
            $minutes = floor($word_count / 150);

            return $minutes.' min read';
        };
    }

    /**
     * Дополнительное преобразование специальных символов в HTML-сущности.
     * @return callable
     */
    public function secureHtml(): callable
    {
        return function (string $text = null): string {
            if (is_null($text)) {
                return '';
            }

            $text = e($text, false);

            $text = str_replace(
                ['{', '}', '<', '>', '"', "'"],
                ['&#123;', '&#125;', '&lt;', '&gt;', '&#34;', '&#039;'],
                $text
            );

            return trim($text);
        };
    }

    /**
     * Удалить HTML-теги и обрезать строку до указанной длины с ограничителем.
     * @return callable
     */
    public function teaser(): callable
    {
        return function (string $text = null, int $length = 255, string $finisher = ' ...'): string {
            if (is_null($text)) {
                return '';
            }

            $text = static::cleanHTML($text);
            $length -= mb_strlen($finisher);

            if ((mb_strlen($text.$finisher) <= $length) or (0 === $length)) {
                return $text;
            }

            $text = mb_substr($text, 0, $length);
            $text = rtrim($text, ' .,:;!?-');

            if (strpos($text, ' ')) {
                $text = mb_substr($text, 0, mb_strrpos($text, ' '));
                $text = rtrim($text, ' .,:;!?-');
            }

            return empty($text) ? $text : $text.$finisher;
        };
    }
}
