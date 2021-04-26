<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Название модели соответствующей фабрики.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Определить состояние модели по умолчанию.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->select('id')->first() ?: User::factory(),
            // 'parent_id' => 0,
            'content' => $this->faker->paragraph(mt_rand(1, 4)),
            'is_approved' => mt_rand(0, 1),
            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
        ];
    }
}
