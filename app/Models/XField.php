<?php

// http://designpatternsphp.readthedocs.io/ru/latest/More/EAV/README.html

namespace App\Models;

use App\Models\Observers\XFieldObserver;

class XField extends BaseModel
{
    use Mutators\XFieldMutators,
        Traits\Dataviewer;

    /**
     * Таблица БД, ассоциированная с моделью.
     * @var string
     */
    protected $table = 'x_fields';

    /**
     * Первичный ключ таблицы БД.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Атрибуты, которые должны быть типизированы.
     * @var array
     */
    protected $casts = [
        'params' => 'array',

    ];

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     * @var array
     */
    protected $fillable = [
        'extensible',
        'name',
        'type',
        'params',
        'title',
        'descr',
        'html_flags',

    ];

    /**
     * Атрибуты, по которым разрешена фильтрация сущностей.
     * @var array
     */
    protected $allowedFilters = [

    ];

    /**
     * Атрибуты, по которым разрешена сортировка сущностей.
     * @var array
     */
    protected $orderableColumns = [
        'id',
        'title',
        'created_at',

    ];

    protected static $xPrefix = 'x_';

    /**
     * Разрешенные имена таблиц в БД,
     * для которых доступно создание новых полей.
     * @var array
     */
    protected static $extensibles = [
        'articles',
        'categories',
        'users',

    ];

    /**
     * Типы дополнительных полей для таблиц в БД.
     * @var array
     */
    protected static $fieldTypes = [
        'string',
        'integer',
        'boolean',
        'array',
        'text',
        'timestamp',

    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(XFieldObserver::class);
    }

    public static function fields($table = null)
    {
        $fields = cache()
            ->rememberForever(static::getModel()->getTable(), function () {
                return static::query()->get();
            });

        return is_null($table) ? $fields : $fields->where('extensible', $table);
    }

    public static function extensibles()
    {
        return static::$extensibles;
    }

    public static function fieldTypes()
    {
        return static::$fieldTypes;
    }

    public function xPrefix()
    {
        return static::$xPrefix;
    }
}
