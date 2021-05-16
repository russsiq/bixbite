<?php

namespace Tests\Fixtures;

class ThemeFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                //
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
