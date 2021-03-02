<?php

namespace Tests\Feature\Api\V1\Articles\Fixtures;

class ArticleFixtures
{
    public static function resource(): array
    {
        return [
            'links' => [
                'self',
            ],
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'user_id',
                    'title', 'slug', 'teaser', 'content',
                    'meta_description', 'meta_keywords', 'meta_robots',
                    'on_mainpage', 'is_favorite', 'is_pinned',
                    'views',
                    'created_at', 'updated_at',
                ],
                'relationships',
            ],
        ];
    }

    public static function collection(): array
    {
        return [
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'user_id',
                        'title', 'slug', 'teaser', 'content',
                        'meta_description', 'meta_keywords', 'meta_robots',
                        'on_mainpage', 'is_favorite', 'is_pinned',
                        'views',
                        'created_at', 'updated_at',
                    ],
                    'relationships',
                ],
            ],
            'links' => [
                'self',
            ],
        ];
    }
}
