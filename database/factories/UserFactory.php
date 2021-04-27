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
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'role' => $this->faker->randomElement([
                'admin', 'moder', 'user',
            ]),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'avatar' => asset('storage/avatars/noavatar.png'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
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
