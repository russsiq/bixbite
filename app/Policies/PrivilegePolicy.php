<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Privilege;

/**
 * Данные привилегии доступны только владельцам сайта.
 */
class PrivilegePolicy extends BasePolicy
{

}
