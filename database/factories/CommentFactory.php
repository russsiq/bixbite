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
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->select('id')->first() ?: User::factory()->createOne(),
            'parent_id' => 0,
            'content' => $this->faker->paragraph(mt_rand(1, 3)),
            'author_name' => null,
            'author_email' => null,
            'author_ip' => null,
            'is_approved' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
        ];
    }
}
