<?php

namespace BBCMS\Models;

use Schema;

use BBCMS\Models\BaseModel;
use BBCMS\Models\Traits\CacheForgetByKeysTrait;

class Privilege extends BaseModel
{
    use CacheForgetByKeysTrait;

    // Очищаем кеш по этим ключам всегда
    // при нахождении в разделе привилегий ад.панели
    protected $keysToForgetCache = [
        'privileges', 'roles', // 'privileges.' . $role
    ];

    protected $table = 'privileges';

    public function roles()
    {
        return $this->getCachedRoles();
    }

    protected function getRoles()
    {
        // Except unnecessary columns with role 'owner'. Not available for editing.

        return array_diff(
            Schema::getColumnListing($this->table),
            ['owner', 'id', 'privilege', 'description', 'created_at', 'updated_at', 'deleted_at']
        );
    }

    protected function getCachedRoles()
    {
        return cache()->rememberForever('roles', function () {
            return $this->getRoles();
        });
    }

    public function saveTable(array $table)
    {
        foreach ($this->roles() as $role) {
            if (isset($table[$role])) {
                $this->whereIn('id', $table[$role])->update([$role => 1]);
                $this->whereNotIn('id', $table[$role])->update([$role => null]);
            } else {
                $this->whereNotNull($role)->update([$role => null]);
            }

            cache()->forget('privileges.' . $role);
        }

        $this->whereNull('owner')->update(['owner' => 1]);
    }
}
