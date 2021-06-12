<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attachment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->unique()->sentence(mt_rand(4, 8));

        return [
            // $table->nullableMorphs('attachable');
            'user_id' => User::inRandomOrder()->select('id')->first() ?: User::factory()->createOne(),
            'title' => $title,
            'description' => $this->faker->text(mt_rand(8, 48)),
            'disk' => 'public',
            'folder' => 'uploads',
            'type' => 'other',
            'name' => Str::slug($title).'_'.time(),
            'extension' => null,
            'mime_type' => Attachment::UNKNOWN_MIME_TYPE,
            'filesize' => 0,
            'properties' => [],
            'downloads' => mt_rand(0, 240),
            'created_at' => null,
            'updated_at' => null,
        ];
    }

    public function imageOnPublicDisk(int $width = 1024, int $height = 768, string $extension = 'jpg')
    {
        return $this->state(function (array $attributes) use ($width, $height, $extension) {

            Storage::fake($disk = 'public');

            $name = $attributes['name'].'.'.$extension;

            $file = UploadedFile::fake()->image('avatar.jpg', $width, $height)->size(100);

            $file->storePubliclyAs('image/uploads', $name, compact('disk'));

            return [
                'disk' => $disk,
                'folder' => 'uploads',
                'type' => 'image',
                'extension' => $extension,
                'mime_type' => $file->getMimeType(),
                'filesize' => $file->getSize(),
                'properties' => [
                    //
                ],
            ];
        });
    }
}
