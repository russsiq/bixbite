<?php

namespace Tests\Fixtures;

class ArticleFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'user_id',
                'image_id',
                'state',
                'title',
                'slug',
                'teaser',
                'content',
                'meta_description',
                'meta_keywords',
                'meta_robots',
                'on_mainpage',
                'is_favorite',
                'is_pinned',
                'is_catpinned',
                'allow_com',
                'views',
                'created_at',
                'updated_at',

                'edit_page_url',
                'is_published',
                // 'raw_content',
                'updated',
                'url',
                'comment_store_url',
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
