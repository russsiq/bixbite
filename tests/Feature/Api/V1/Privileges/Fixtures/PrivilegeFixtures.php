<?php

namespace Tests\Feature\Api\V1\Privileges\Fixtures;

class PrivilegeFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'privilege',
                'description',
                'owner',
                'admin',
                'moder',
                'user',
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
