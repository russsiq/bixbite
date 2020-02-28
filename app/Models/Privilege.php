<?php

namespace BBCMS\Models;

// Зарегистрированные фасады приложения.
use Schema;

// Сторонние зависимости.
use BBCMS\Models\Observers\PrivilegeObserver;

class Privilege extends BaseModel
{
    /**
     * Таблица БД, ассоциированная с моделью.
     * @var string
     */
    protected $table = 'privileges';

    /**
     * Первичный ключ таблицы БД.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * User exposed observable events.
     * These are extra user-defined events observers may subscribe to.
     * @var array
     */
    protected $observables = [
        'tableUpdated',

    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(PrivilegeObserver::class);
    }

    public function roles()
    {
        return cache()->rememberForever('roles', function () {
            return array_diff(Schema::getColumnListing($this->table), [
                'id',
                'privilege',
                'description',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
        });
    }

    public function privileges()
    {
        return cache()->rememberForever('privileges', function () {
            return $this->select([
                    'privilege',
                ])
                ->addSelect(
                    $this->roles()
                )
                ->get()
                ->mapWithKeys(function ($item) {
                    $out = array_filter($item->toArray());

                    unset($out['privilege']);

                    return [
                        $item['privilege'] => $out
                    ];
                })
                ->toArray();
        });
    }

    public function saveTable(array $table)
    {
        $roles = array_diff($this->roles(), [
            'owner',
        ]);

        foreach ($roles as $role) {
            if (isset($table[$role])) {
                $this->whereIn('id', $table[$role])->update([$role => 1]);
                $this->whereNotIn('id', $table[$role])->update([$role => null]);
            } else {
                $this->whereNotNull($role)->update([$role => null]);
            }
        }

        $this->whereNull('owner')->update(['owner' => 1]);

        $this->fireModelEvent('tableUpdated', false);
    }
}
