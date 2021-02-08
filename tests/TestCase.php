<?php

namespace Tests;

use App\Models\User;
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
            ->create(array_merge([

            ], $attributes));
    }
}
