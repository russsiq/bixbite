<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * Название модели соответствующей фабрики.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Определить состояние модели по умолчанию.
     *
     * @return array
     */
    public function definition(): array
    {
        // $title = $faker->sentence(mt_rand(1, 2));
        $title = $this->faker->unique()->word;

        $date = now()
            ->subDays(mt_rand(1, 720))
            ->addSeconds(mt_rand(1, 86400))
            ->format('Y-m-d H:i:s');

        return [
            'title' => Str::ucfirst($title),
            'slug' => Str::slug($title),
            'info' => $this->faker->text(mt_rand(120, 255)),
            'created_at' => $date,
            'updated_at' => $date,

        ];
    }
}
