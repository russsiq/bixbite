<?php

namespace Database\Factories;

use App\Models\XField;
use Illuminate\Database\Eloquent\Factories\Factory;
use InvalidArgumentException;

class XFieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = XField::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $limit = XField::limitLengthNameColumn();

        return [
            'extensible' => $this->faker->randomElement(XField::extensibles()),
            'name' => $this->faker->unique()->regexify("/^[a-z0-9_]{2,{$limit}}$/"),
            'type' => $this->faker->randomElement(XField::fieldTypes()),
            'params' => [],
            'title' => $this->faker->text(mt_rand(8, 48)),
            'description' => null,
            'html_flags' => [],
            'created_at' => null,
            'updated_at' => null,
        ];
    }

    /**
     * Define a field with the specified `type`.
     *
     * @param  string  $type
     * @return $this
     */
    public function withSpecifiedType(string $type): self
    {
        if (! in_array($type, XField::fieldTypes())) {
            throw new InvalidArgumentException(trans('Unknown extra field type: [:type].', [
                'type' => $type,
            ]));
        }

        return $this->state(
            fn (array $attributes) => [
                'type' => $type,
            ]
        );
    }

    /**
     * Define a field with the specified `extensible`.
     *
     * @param  string  $extensible
     * @return $this
     */
    public function withSpecifiedExtensible(string $extensible): self
    {
        if (! in_array($extensible, XField::extensibles())) {
            throw new InvalidArgumentException(trans('Unknown extensible extra fields table: [:extensible].', [
                'extensible' => $extensible,
            ]));
        }

        return $this->state(
            fn (array $attributes) => [
                'extensible' => $extensible,
            ]
        );
    }
}
