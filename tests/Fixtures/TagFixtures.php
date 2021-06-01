<?php

namespace Tests\Fixtures;

class TagFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'title',
                'slug',

                'url',
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
