<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_password_screen_can_be_rendered()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->get(route('password.confirm'))
            ->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->post(route('password.confirm'), [
                'password' => 'password',
            ])
            ->assertStatus(302)
            ->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->post(route('password.confirm'), [
                'password' => 'wrong-password',
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'password'
            ]);
    }
}
