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

        return preg_match('/^[[:alnum:][:space:]\_\-]{3,255}$/u', $value) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $locale = $this->translator->getLocale();

        $message = [
            'en' => 'The :attribute may only contain letters, numbers, dashes, underscores and whitespaces.',
            'ru' => 'Поле :attribute может содержать только буквы, цифры, дефис, нижнее подчеркивание и пробелы.',
        ];

        return $message[$locale] ?? $message['ru'];
    }
}
