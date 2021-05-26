<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(mt_rand(2, 5));

        return [
            'image_id' => null,
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(),
            'is_completed' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
        ];
    }

    public function incompleted()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_completed' => false,
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_completed' => true,
            ];
        });
    }
}
