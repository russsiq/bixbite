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
        $article = Article::inRandomOrder()
            ->select('id')
            ->published()
            ->first();

        // Если нет пользователей, то создадим нового.
        $user = User::inRandomOrder()->select('id')->first()
            ?? User::factory()->create();

        $date = now()
            ->subDays(mt_rand(1, 720))
            ->addSeconds(mt_rand(1, 86400))
            ->format('Y-m-d H:i:s');

        return [
            'user_id' => $user->id,
            'commentable_type' => 'articles',
            'commentable_id' => $article->id,
            'content' => $this->faker->paragraph(mt_rand(1, 4)),
            'is_approved' => mt_rand(0, 1),
            'created_at' => $date,
            'updated_at' => $date,

        ];
    }
}
