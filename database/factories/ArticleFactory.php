<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->unique()->sentence(mt_rand(4, 12));

        $content = '';

        foreach (range(1, mt_rand(8, 20)) as $step) {
            $content .= '<p>'.$this->faker->paragraph.'</p>';
        }

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'teaser' => $this->faker->text(mt_rand(120, 255)),
            'content' => $content,

            'meta_description' => $this->faker->text(mt_rand(120, 255)),
            'meta_keywords' => implode(',', $this->faker->words(mt_rand(3, 8))),
            'meta_robots' => $this->faker->randomElement(['all', 'noindex', 'nofollow', 'none']),

            'views' => mt_rand(0, 240),

            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
        ];
    }
}
