<?php

namespace App\Http\Requests;

// Сторонние зависимости.
use Illuminate\Foundation\Http\FormRequest;

/**
 * Абстрактынй класс обработки запросов пользовательских форм.
 * @var class
 */
abstract class BaseFormRequest extends FormRequest
{
    /**
     * Общий массив допустимых значений для правила `in:список_значений`.
     * @var array
     */
    protected $allowedForInRule = [
        // 'key' => ['one', 'two', 'other'],
    ];

    /**
     * Получить список допустимых значений для правила `in:список_значений`.
     * @param  string  $key
     * @param  array  $exclude  Массив исключаемых значений.
     * @return string
     */
    protected function allowedForInRule(string $key, array $exclude = []): string
    {
        $allowed = $this->allowedForInRule[$key];

        if ($exclude) {
            $allowed = array_diff($allowed, $exclude);
        }

        return implode(',', $allowed);
    }
}
