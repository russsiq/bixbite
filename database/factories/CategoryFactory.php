<?php

namespace Database\Factories;

use App\Models\Category;
use App\Rules\MetaRobotsRule;
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
        $title = $this->faker->unique()->words(mt_rand(1, 3), true);

        return [
            'title' => Str::ucfirst($title),
            'slug' => Str::slug($title),
            'info' => '<p>'.$this->faker->paragraph().'</p>',

            'description' => $this->faker->text(mt_rand(120, 255)),
            'keywords' => implode(',', $this->faker->words(mt_rand(4, 8))),
            'robots' => $this->faker->randomElement(MetaRobotsRule::DIRECTIVES),

            'show_in_menu' => mt_rand(0, 1),
            'paginate' => mt_rand(5, 20),
            // 'template' => 'string',
            'order_by' => 'id',
            'direction' => $this->faker->randomElement(['desc', 'asc']),

            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
        ];
    }
}
