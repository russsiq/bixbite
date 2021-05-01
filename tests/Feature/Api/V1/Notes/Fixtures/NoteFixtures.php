<?php

namespace Tests\Feature\Api\V1\Notes\Fixtures;

class NoteFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'user_id',
                'image_id',
                'title',
                'slug',
                'description',
                'is_completed',
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
