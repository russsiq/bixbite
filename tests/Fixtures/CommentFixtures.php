<?php

namespace Tests\Fixtures;

class CommentFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'user_id',
                'parent_id',
                'commentable_id',
                'commentable_type',
                'content',
                'author_name',
                'author_email',
                'author_ip',
                'is_approved',
                'created_at',
                'updated_at',

                'url',
                'by_user',
                'author',
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
