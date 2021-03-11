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

    protected function loginSuperAdminSPA(array $attributes = [], array $abilities = []): User
    {
        [$first_email] = explode(',', env('APP_SUPER_ADMINS'), 2);

        $attributes['email'] = $first_email;

        return $this->loginSPA($attributes, $abilities);
    }

    protected function createUser(array $attributes = []): User
    {
        return User::factory()
            ->create(array_merge([

            ], $attributes));
    }

    protected function currentAuthenticatedUser($guard = null): User
    {
        return $this->app->make('auth')->guard($guard)->user();
    }
}
