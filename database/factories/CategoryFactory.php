<?php

namespace Database\Factories;

use App\Models\Category;
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
    public function definition()
    {
        $title = $this->faker->unique()->words(mt_rand(1, 3), true);

        return [
            // 'parent_id' => 0,
            // 'position' => 0,

            'title' => Str::ucfirst($title),
            'slug' => Str::slug($title),
            // 'alt_url' => null,
            'info' => '<p>'.$this->faker->paragraph.'</p>',

            'meta_description' => $this->faker->text(mt_rand(120, 255)),
            'meta_keywords' => implode(',', $this->faker->words(mt_rand(3, 8))),
            'meta_robots' => $this->faker->randomElement(['all', 'noindex', 'nofollow', 'none']),

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
