<?php

namespace BBCMS\Policies;

use BBCMS\Models\User;
use BBCMS\Models\Privilege;

/**
 * Данные привилегии доступны только владельцам сайта.
 */
class PrivilegePolicy extends BasePolicy
{

}
