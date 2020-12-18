<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * Название модели соответствующей фабрики.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Определить состояние модели по умолчанию.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->word,

        ];
    }
}

// $tags = [
//     ['title' => 'Бизнес', ],
//     ['title' => 'Политика', ],
//     ['title' => 'Спорт', ],
//     ['title' => 'Мода', ],
//     ['title' => 'Развлечения', ],
//     ['title' => 'Вне категории', ],
// ];
