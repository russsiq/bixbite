<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->select('id')->first() ?: User::factory(),
            // 'parent_id' => 0,
            'commentable_type' => 'articles',
            'commentable_id' => Article::inRandomOrder()->select('id')->first() ?: Article::factory(),
            'content' => $this->faker->paragraph(mt_rand(1, 4)),
            'is_approved' => mt_rand(0, 1),
            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
        ];
    }
}
