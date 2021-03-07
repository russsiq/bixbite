<?php

namespace App\Rules;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\Rule;

class MetaRobotsRule implements Rule, ImplicitRule
{
    /**
     * Valid indexing directives for the robots meta tag.
     *
     * @const string[]
     * */
    public const DIRECTIVES = [
        'all', 'noindex', 'nofollow', 'none',
    ];

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
        return is_string($value)
            && in_array($value, self::DIRECTIVES);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->translator->get('validation.in');
    }
}
