<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get(route('login'))
            ->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = $this->createUser();

        $response = $this->post(route('login'), [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertRedirect(
                RouteServiceProvider::HOME
            );

        $user = $this->assertAuthenticated()
            ->currentAuthenticatedUser();
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = $this->createUser();

        $response = $this->post(route('login'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'email' => trans('auth.failed'),
            ]);

        $this->assertGuest();
    }
}
