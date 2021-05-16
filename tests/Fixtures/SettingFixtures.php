<?php

namespace Tests\Fixtures;

class SettingFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'module_name',
                'name',
                'type',
                'value',
                'created_at',
                'updated_at',
            ],
        ];
    }

    public static function collection(): array
    {
        return [
            'data' => [
                '*' => static::resource()['data'],
            ],
            'links' => [
                //
            ],
            // 'meta' => [
            //     //
            // ],
        ];
    }
}
