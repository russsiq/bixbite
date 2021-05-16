<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var string|null */
    protected $firstSuperAdminEmail;

    protected function loginSPA(array $attributes = [], array $abilities = []): User
    {
        $user = Sanctum::actingAs(
            $this->createUser($attributes), $abilities
        );

        return $user;
    }

    protected function loginSuperAdminSPA(array $attributes = [], array $abilities = []): User
    {
        $attributes['email'] = $this->getSuperAdminEmail();

        return $this->loginSPA($attributes, $abilities);
    }

    protected function loginSuperAdmin(array $attributes = [], string $guard = null): User
    {
        $attributes['email'] = $this->getSuperAdminEmail();

        $this->actingAs(
            $user = $this->createUser($attributes), $guard
        );

        return $user;
    }

    protected function createUser(array $attributes = []): User
    {
        return User::factory()
            ->create(array_merge([

            ], $attributes));
    }

    protected function currentAuthenticatedUser(string $guard = null): User
    {
        return $this->app->make('auth')->guard($guard)->user();
    }

    protected function getSuperAdminEmail(): string
    {
        return $this->firstSuperAdminEmail
            ?: $this->firstSuperAdminEmail = head(
                explode(',', env('APP_SUPER_ADMINS'), 2)
            );
    }
}
