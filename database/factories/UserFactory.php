<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement([
                'admin', 'moder', 'user',
            ]),
            'avatar' => null,
            'info' => null,
            'location' => null,
            'last_ip' => null,
            'logined_at' => null,
            'banned_until' => null,
            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
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
