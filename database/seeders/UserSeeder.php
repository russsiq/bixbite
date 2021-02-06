<?php

namespace Database\Seeders;

use App\Models\Team;
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
            ->hasAttached(
                Team::factory()
                    ->state(function (array $attributes, User $user) {
                        return [
                            'user_id' => $user->id,
                            'personal_team' => true,
                        ];
                    })
            )
            ->count($countToSeed ?: self::COUNT_TO_SEED)
            ->create();
    }
}
