<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_profile_information_is_available()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('profile.show'));

        $response->assertViewHasAll([
            'user' => $user
        ]);
    }

    public function test_profile_information_can_be_updated()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->put(route('user-profile-information.update', [
                'name' => 'Test Name', 'email' => 'test@example.com'
            ]));

        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
    }
}
