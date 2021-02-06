<?php

namespace Tests;

use App\Models\Team;
use App\Models\User;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function loginSPA(array $attributes = [], array $abilities = []): User
    {
        $user = Sanctum::actingAs(
            $this->createUser($attributes),
            $abilities
        );

        return $user;
    }

    protected function createUser(array $attributes = []): User
    {
        return User::factory()
            ->hasAttached(
                $this->newTeamInstance()
            )
            ->create(array_merge([

            ], $attributes));
    }

    protected function newTeamInstance(): TeamFactory
    {
        return Team::factory()
            ->state(function (array $attributes, User $user) {
                return [
                    'user_id' => $user->id,
                    'personal_team' => true,
                ];
            });
    }
}
