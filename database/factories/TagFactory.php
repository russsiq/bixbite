<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $title = $this->faker->unique()->words(mt_rand(1, 3), true);

        return [
            'title' => Str::ucfirst($title),
            'slug' => Str::slug($title),
            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
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
