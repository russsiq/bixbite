<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Setting;

/**
 * Данные привилегии доступны только владельцам сайта.
 */
class SettingPolicy extends BasePolicy
{
    public function getModule(User $user)
    {
        return $this->default(...func_get_args());
    }

    public function updateModule(User $user)
    {
        return $this->default(...func_get_args());
    }
}
