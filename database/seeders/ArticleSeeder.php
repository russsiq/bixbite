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

        Article::factory()
            ->count($countToSeed ?: self::COUNT_TO_SEED)
            ->state(new Sequence(
                ...$users
            ))
            ->create();
    }

    protected function getUsersCollection(): Collection
    {
        return UserSeeder::getExistingCollection(['id'])
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

        return Article::select($attributes)
            ->take(self::COUNT_TO_SEED)
            ->get();
    }

    public static function ensureMinimumSeeding()
    {
        $count = Article::count();

        $missingCount = static::COUNT_TO_SEED - $count;

        if ($missingCount > 0) {
            (new static)->callWith(static::class, [
                'countToSeed' => $missingCount
            ]);
        }
    }
}
