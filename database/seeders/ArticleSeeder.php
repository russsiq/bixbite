<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ArticleSeeder extends Seeder
{
    /**
     * The number of models that should be generated.
     *
     * @const int
     */
    public const COUNT_TO_SEED = 120;

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

        $article = Article::factory()
            ->count($countToSeed ?: self::COUNT_TO_SEED)
            ->state(new Sequence(
                ...$users
            ))
            ->create();
    }

    protected function getUsersCollection(): Collection
    {
        $this->prepareUsersTable();

        return User::inRandomOrder()
            ->select('id')
            ->take(UserSeeder::COUNT_TO_SEED)
            ->get()
            ->map(function (User $user) {
                return [
                    'user_id' => $user->id,
                    'team_id' => $user->currentTeam->id,
                ];
            });
    }

    public function prepareUsersTable()
    {
        $usersCount = User::count();

        $missingUsersCount = UserSeeder::COUNT_TO_SEED - $usersCount;

        if ($missingUsersCount > 0) {
            $this->callWith(UserSeeder::class, [
                'countToSeed' => $missingUsersCount
            ]);
        }
    }
}
