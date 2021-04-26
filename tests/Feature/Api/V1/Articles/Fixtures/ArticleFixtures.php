<?php

namespace Tests\Feature\Api\V1\Articles\Fixtures;

class ArticleFixtures
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
        ];
    }
}
