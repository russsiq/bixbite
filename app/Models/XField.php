<?php

namespace BBCMS\Models;

use BBCMS\Models\BaseModel;

use BBCMS\Models\Mutators\XFieldMutators;
use BBCMS\Models\Observers\XFieldObserver;

class XField extends BaseModel
{
    use XFieldMutators;

    protected $primaryKey = 'id';
    protected $table = 'x_fields';
    protected $casts = [
        //
    ];
    protected $appends = [
        //
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

    protected static $x_prefix = 'x_';
    protected static $extensibles = [
        'articles',
        'categories',
        'users',
    ];
    protected static $field_types = [
        'boolean',
        'integer',
        'string',
        'text',
        'timestamp',
        'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(XFieldObserver::class);
    }

    public static function fields($table = null)
    {
        $fields = cache()->rememberForever('x_fields', function () {
            return static::query()->get();
        });

        return is_null($table)
            ? $fields
            : $fields->where('extensible', $table);
    }

    public static function extensibles()
    {
        return static::$extensibles;
    }

    public static function fieldTypes()
    {
        return static::$field_types;
    }

    public function xPrefix()
    {
        return static::$x_prefix;
    }
}
