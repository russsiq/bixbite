<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_updated()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->put(route('user-password.update', [
                'current_password' => 'password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]))
            ->assertStatus(302)
            ->assertSessionHas('status', 'password-updated');

        $user = $user->fresh();

        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_current_password_must_be_correct()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->put(route('user-password.update', [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]))
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'current_password',
            ], errorBag: 'updatePassword');

        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_new_passwords_must_match()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->put(route('user-password.update', [
                'current_password' => 'password',
                'password' => 'new-password',
                'password_confirmation' => 'wrong-password',
            ]))
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'password',
            ], errorBag: 'updatePassword');

        $this->assertTrue(Hash::check('password', $user->password));
    }
}
