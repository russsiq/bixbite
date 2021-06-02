<?php

namespace App\Models\Observers;

use App\Models\Privilege;

class PrivilegeObserver extends BaseObserver
{
    /**
     * Массив ключей для очистки кэша.
     * @var array
     */
    protected $keysToForgetCache = [
        'privileges' => 'privileges',
        'roles' => 'roles',

    ];

    /**
     * Обработать событие `tableUpdated` модели.
     * @param  Privilege  $privilege
     * @return void
     */
    public function tableUpdated(Privilege $privilege)
    {
        $this->forgetCacheByKeys($privilege);
    }
}
