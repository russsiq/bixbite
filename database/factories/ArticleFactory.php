<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
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
        $title = $this->faker->unique()->sentence(mt_rand(4, 12));

        $content = '';

        foreach (range(1, mt_rand(8, 20)) as $step) {
            $content .= '<p>'.$this->faker->paragraph.'</p>';
        }

        // Если нет пользователей, то создадим нового.
        $user = User::inRandomOrder()->select('id')->first()
            ?? User::factory()->create();

        $date = now()
            ->subDays(mt_rand(1, 720))
            ->addSeconds(mt_rand(1, 86400))
            ->format('Y-m-d H:i:s');

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'teaser' => $this->faker->text(mt_rand(120, 255)),
            'content' => $content,
            'user_id' => $user->id,
            // 'img' => $this->faker->randomElement($images),
            'state' => $this->faker->randomElement([
                'published',
                'unpublished',
                'draft',

            ]),
            'created_at' => $date,
            'updated_at' => $date,

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
