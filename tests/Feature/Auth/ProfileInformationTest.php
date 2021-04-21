<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_profile_information_is_available()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertStatus(200)
            ->assertViewHasAll([
                'user' => $user
            ]);
    }

    public function test_profile_information_can_be_updated()
    {
        Notification::fake();

        $user = $this->createUser([
            'name' => 'First Test Name',
            'email' => 'first@example.com',
        ]);

        $response = $this->actingAs($user)
            ->put(route('user-profile-information.update', [
                'name' => $expectedName = 'Second Test Name',
                'email' => $expectedEmail = 'second@example.com',
            ]))
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $user = $user->fresh();

        $this->assertEquals($expectedName, $user->name);

        $this->assertEquals($expectedEmail, $user->email);

        $this->assertInstanceOf(MustVerifyEmail::class, $user);

        $this->assertFalse($user->hasVerifiedEmail());

        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }
}
