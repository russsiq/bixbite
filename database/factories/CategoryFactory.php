<?php

namespace Database\Factories;

use App\Models\Category;
use App\Rules\MetaRobotsRule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(mt_rand(1, 3));

        return [
            'image_id' => null,
            'parent_id' => 0,
            'position' => 1,
            'title' => Str::ucfirst($title),
            'slug' => Str::slug($title),
            'alt_url' => null,
            'info' => $this->faker->paragraph(),
            'meta_description' => $this->faker->text(mt_rand(120, 255)),
            'meta_keywords' => implode(',', $this->faker->words(mt_rand(4, 8))),
            'meta_robots' => $this->faker->randomElement(MetaRobotsRule::DIRECTIVES),
            'show_in_menu' => $this->faker->boolean(),
            'order_by' => 'id',
            'direction' => $this->faker->randomElement(['desc', 'asc']),
            'paginate' => mt_rand(5, 20),
            // 'template' => 'string',
            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
        ];
    }
}
