<?php

namespace Tests\Fixtures;

class AttachmentFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'attachable_id',
                'attachable_type',
                'user_id',
                'title',
                'description',
                'disk',
                'folder',
                'type',
                'name',
                'extension',
                'mime_type',
                'filesize',
                'properties',
                'downloads',
                'created_at',
                'updated_at',

                'url',
                'path',
                'absolute_path',
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
