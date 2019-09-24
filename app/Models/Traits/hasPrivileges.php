<?php

namespace BBCMS\Models\Traits;

use BBCMS\Models\Privilege;

trait hasPrivileges
{


    /**
     * Проверить, относится ли пользователь к переданной группе.
     * @param string $role Группа пользователей.
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $role === $this->role;
    }

    /**
     * Проверить, может ли пользователь, относящийся к определенной группе,
     * выполнить операцию, соответствующую переданной привилегии.
     * @param string $privilege Привилегия.
     * @return bool
     */
    public function canDo(string $privilege): bool
    {
        // $privileges = CacheFile::fromMap('privileges');
        $privileges = Privilege::getModel()->privileges();

        return $privileges[$privilege][$this->role] ?? false;
        
        return $this->hasRole('owner') or ($privileges[$privilege][$this->role] ?? false);
    }
}
