<?php

namespace Tests\Fixtures;

class CategoryFixtures
{
    public static function resource(): array
    {
        return [
            'data' => [
                'id',
                'image_id',
                'parent_id',
                'position',
                'title',
                'slug',
                'alt_url',
                'info',
                'meta_description',
                'meta_keywords',
                'meta_robots',
                'show_in_menu',
                'order_by',
                'direction',
                'paginate',
                'template',
                'created_at',
                'updated_at',

                'url',
                'edit_page',
                'is_root',
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
