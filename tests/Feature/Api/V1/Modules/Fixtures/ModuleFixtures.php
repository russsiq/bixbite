<?php

namespace Tests\Feature\Api\V1\Modules\Fixtures;

class ModuleFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'name',
                'title',
                'icon',
                'info',
                'on_mainpage',
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
