<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Название модели соответствующей фабрики.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Определить состояние модели по умолчанию.
     *
     * @return array
     */
    public function definition(): array
    {
        $name = $this->faker->name;

        return [
            'name' => $name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'role' => $this->faker->randomElement([
                'admin',
                'moder',
                'user',

            ]),
            'password' => $name.$name,
            'remember_token' => Str::random(10),

        ];
    }

    /**
     * Указать, что пользователь является собствеником.
     *
     * @return Factory
     */
    public function asOwner(): Factory
    {
        return $this->state([
            'role' => 'owner',

        ]);
    }

    /**
     * Указать, что пользователь является Администратором.
     *
     * @return Factory
     */
    public function asAdmin(): Factory
    {
        return $this->state([
            'role' => 'admin',
        ]);
    }

    /**
     * Указать, что пользователь является Модератором.
     *
     * @return Factory
     */
    public function asModer(): Factory
    {
        return $this->state([
            'role' => 'moder',

        ]);
    }

    /**
     * Указать, что пользователь является обычным пользователем.
     *
     * @return Factory
     */
    public function asUser(): Factory
    {
        return $this->state([
            'role' => 'user',

        ]);
    }
}
