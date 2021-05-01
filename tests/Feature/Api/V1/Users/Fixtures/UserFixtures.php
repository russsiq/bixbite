<?php

namespace Tests\Feature\Api\V1\Users\Fixtures;

class UserFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'password',
                'remember_token',
                'created_at',
                'updated_at',

                'role',
                'avatar',
                'info',
                'location',
                'last_ip',
                'logined_at',
                'banned_until',

                'profile',
                'is_online',
                'logined',
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
