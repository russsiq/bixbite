<?php

// http://designpatternsphp.readthedocs.io/ru/latest/More/EAV/README.html

namespace BBCMS\Models;

use BBCMS\Models\Observers\XFieldObserver;

class XField extends BaseModel
{
    use Mutators\XFieldMutators,
        Traits\Dataviewer;

    protected $primaryKey = 'id';

    protected $table = 'x_fields';

    protected $casts = [
        'params' => 'array',
    ];

    protected $fillable = [
        'extensible',
        'name',
        'type',
        'params',
        'title',
        'descr',
        'html_flags',
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

    protected $allowedFilters = [
        //
    ];

    protected $orderableColumns = [
        'id',
        'title',
        'created_at',
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
