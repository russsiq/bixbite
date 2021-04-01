<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;

class UserSeeder extends Seeder
{
    /**
     * The number of models that should be generated.
     *
     * @const int
     */
    public const COUNT_TO_SEED = 50;

    /**
     * Run the database seeds.
     *
     * @param  int  $countToSeed
     * @return void
     */
    public function run(int $countToSeed = null): void
    {
        Mail::fake();

        User::factory()
            ->count($countToSeed ?: self::COUNT_TO_SEED)
            ->create();
    }

    public static function getExistingCollection(array $attributes = ['*']): Collection
    {
        self::ensureMinimumSeeding();

        return User::select($attributes)
            ->take(self::COUNT_TO_SEED)
            ->get();
    }

    public static function ensureMinimumSeeding()
    {
        $count = User::count();

        $missingCount = static::COUNT_TO_SEED - $count;

        if ($missingCount > 0) {
            (new static)->callWith(static::class, [
                'countToSeed' => $missingCount
            ]);
        }
    }
}
