<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tag;

/**
 * Данные привилегии доступны только владельцам сайта.
 */
class TagPolicy extends BasePolicy
{
}
