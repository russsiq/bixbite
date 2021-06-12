<?php

namespace Database\Factories;

use App\Models\Article;
use App\Rules\MetaRobotsRule;
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
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(mt_rand(4, 8));

        return [
            'image_id' => null,
            'state' => mt_rand(0, 2),
            'title' => $title,
            'slug' => Str::slug($title),
            'teaser' => $this->faker->text(mt_rand(120, 255)),

            'content' => implode('', array_map(function () {
                    return '<p>'.$this->faker->paragraph().'</p>';
                }, range(1, mt_rand(8, 20)))),

            'meta_description' => $this->faker->text(mt_rand(120, 255)),
            'meta_keywords' => implode(',', $this->faker->words(mt_rand(4, 8))),
            'meta_robots' => $this->faker->randomElement(MetaRobotsRule::DIRECTIVES),
            'on_mainpage' => $this->faker->boolean(),
            'is_favorite' => $this->faker->boolean(),
            'is_pinned' => $this->faker->boolean(),
            'is_catpinned' => $this->faker->boolean(),
            'allow_com' => mt_rand(0, 2),
            'views' => mt_rand(0, 240),
            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
        ];
    }

    public function drafts()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 0,
            ];
        });
    }

    public function unPublished()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 2,
            ];
        });
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 1,
            ];
        });
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
