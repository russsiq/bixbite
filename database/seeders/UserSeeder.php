<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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
        User::factory()
            ->count($countToSeed ?: self::COUNT_TO_SEED)
            ->create();
    }

    public static function ensureMinimumSeeding()
    {
        $usersCount = User::count();

        $missingUsersCount = static::COUNT_TO_SEED - $usersCount;

        if ($missingUsersCount > 0) {
            (new static)->callWith(static::class, [
                'countToSeed' => $missingUsersCount
            ]);
        }
    }
}
