<?php

namespace Tests\Fixtures;

class TemplateFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'filename',
                'path',
                'exists',
                'content',
                'modified',
                'size',
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
