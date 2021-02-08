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

        $date = now()->subDays(mt_rand(1, 720))
            ->addSeconds(mt_rand(1, 86400))
            ->format('Y-m-d H:i:s');

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'teaser' => $this->faker->text(mt_rand(120, 255)),
            'content' => $content,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
