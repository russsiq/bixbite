<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\ImplicitRule;

class TitleRule implements Rule, ImplicitRule
{
    /** @var Translator */
    protected $translator;

    /**
     * @see https://www.php.net/manual/ru/regexp.reference.unicode.php
     * @see http://unicode.org/reports/tr18/#General_Category_Property
     *
     * @var string[]
     */
    protected $criterias = [
        '\p{L}',
        '\p{M}',
        '\p{Nd}',
        '\p{Pc}\p{Pd}',
        '\p{Sc}',
        '\p{Zs}',
        '«»:;,·\'\!\?\.',
    ];

    /** @var int[] */
    protected $length = [3, 255];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        return preg_match($this->buildPattern(), $value) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        $locale = $this->translator->getLocale();

        $message = [
            'en' => 'The :attribute may only contain letters, numbers, dashes, underscores and whitespaces.',
            'ru' => 'Поле :attribute может содержать только буквы, цифры, дефис, нижнее подчеркивание и пробелы.',
        ];

        return $message[$locale] ?? $message['ru'];
    }

    /**
     * Generate a search pattern string.
     *
     * @return string
     */
    protected function buildPattern(): string
    {
        $criterias = array_values($this->criterias);

        return sprintf(
            '/^[%s]{%d,%d}$/u',
            implode('', $criterias),
            $this->length[0],
            $this->length[1]
        );
    }
}
