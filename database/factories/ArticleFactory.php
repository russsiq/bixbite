<?php

namespace Database\Factories;

use App\Models\Article;
use App\Rules\MetaRobotsRule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * Название модели соответствующей фабрики.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Определить состояние модели по умолчанию.
     *
     * @return array
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(mt_rand(4, 8));

        return [
            'state' => $this->faker->randomElement([
                'published',
                'unpublished',
                'draft',
            ]),

            'title' => $title,
            'slug' => Str::slug($title),
            'teaser' => $this->faker->text(mt_rand(120, 255)),
            'content' => implode(',', array_map(function () {
                    return '<p>'.$this->faker->paragraph().'</p>';
                }, range(1, mt_rand(8, 20)))),

            'description' => $this->faker->text(mt_rand(120, 255)),
            'keywords' => implode(',', $this->faker->words(mt_rand(4, 8))),
            'robots' => $this->faker->randomElement(MetaRobotsRule::DIRECTIVES),

            'views' => mt_rand(0, 240),

            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
        ];
    }
}

// $images = [
//     '{"min":"scarlet-130x90.jpg","max":"scarlet-390x205.jpg","full":"scarlet-800x445.jpg"}',
//     '{"min":"lorem11-130x90.jpg","max":"lorem11-390x205.jpg","full":"lorem11-800x445.jpg"}',
//     '{"min":"lorem23-130x90.jpg","max":"lorem23-390x205.jpg","full":"lorem23-800x445.jpg"}',
//     '{"min":"beauty-130x90.jpg","max":"beauty-390x205.jpg","full":"beauty-800x445.jpg"}',
//     '{"min":"coffee-130x90.jpg","max":"coffee-390x205.jpg","full":"coffee-800x445.jpg"}',
//     '{"min":"girl-130x90.jpg","max":"girl-390x205.jpg","full":"girl-800x445.jpg"}',
//     '{"min":"model-130x90.jpg","max":"model-390x205.jpg","full":"model-800x445.jpg"}',
//     '{"min":"relay-race-130x90.jpg","max":"relay-race-390x205.jpg","full":"relay-race-800x445.jpg"}',
// ];
