<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->words(mt_rand(1, 3), true);

        return [
            'title' => Str::ucfirst($title),
            'slug' => Str::slug($title),
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
