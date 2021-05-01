<?php

namespace Tests\Feature\Api\V1\XFields\Fixtures;

class XFieldFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'extensible',
                'name',
                'type',
                'params',
                'title',
                'descr',
                'html_flags',
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
