<?php

namespace App\Rules;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Rule;

class SqlTextLength implements Rule
{
    /** @var Translator */
    protected $translator;

    /** @var int */
    protected $length = 65535;

    /**
     * Create a new rule instance.
     *
     * @param Translator  $translator
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

        return $this->length > strlen($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->translator->get('validation.lt.file', [
            'value' => round($this->length / 1024),
        ]);
    }
}
