<?php

namespace App\Actions\User;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRulesTrait
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', new Password, 'confirmed'];
    }
}
