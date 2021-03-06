<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class NotesTableSeeder extends Seeder
{
    /**
     * The number of models that should be generated.
     *
     * @const int
     */
    public const COUNT_TO_SEED = 18;

    /**
     * Run the database seeds.
     *
     * @param  int  $countToSeed
     * @return void
     */
    public function run(int $countToSeed = null): void
    {
        $users = $this->getUsersCollection()
            ->toArray();

        Note::factory()
            ->count($countToSeed ?: self::COUNT_TO_SEED)
            ->state(new Sequence(
                ...$users
            ))
            ->create();
    }

    protected function getUsersCollection(): Collection
    {
        return UsersTableSeeder::getExistingCollection(['id'])
            ->shuffle()
            ->map(function (User $user) {
                return [
                    'user_id' => $user->id,
                ];
            });
    }

    public static function getExistingCollection(array $attributes = ['*']): Collection
    {
        self::ensureMinimumSeeding();

        return Note::select($attributes)
            ->take(self::COUNT_TO_SEED)
            ->get();
    }

    public static function ensureMinimumSeeding(): void
    {
        $count = Note::count();

        $missingCount = static::COUNT_TO_SEED - $count;

        if ($missingCount > 0) {
            (new static)->callWith(static::class, [
                'countToSeed' => $missingCount
            ]);
        }
    }
}
