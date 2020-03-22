<?php

namespace App\Policies;

use App\Models\User;
use App\Models\XField;

/**
 * Данные привилегии доступны только владельцам сайта.
 */
class XFieldPolicy extends BasePolicy
{

}
