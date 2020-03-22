<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Template;

/**
 * Данные привилегии доступны только владельцам сайта.
 */
class TemplatePolicy extends BasePolicy
{

}
