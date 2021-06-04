<?php

namespace Tests\Fixtures;

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
                'params' => [],
                'title',
                'descr',
                'html_flags' => [],
                'created_at',
                'updated_at',

                'inline_html_flags',
                'raw_html_flags' => [],
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
