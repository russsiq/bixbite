<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/** @cmd vendor\bin\phpunit Tests\Feature\Auth\EmailVerificationTest.php */
class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered()
    {
        $user = $this->createUser([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->get(route('verification.notice'))
            ->assertStatus(200);
    }

    public function test_email_can_be_verified()
    {
        Event::fake([
            Verified::class,
        ]);

        $user = $this->createUser([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)
            ->get($verificationUrl)
            ->assertRedirect(
                RouteServiceProvider::HOME.'?verified=1'
            );

        $user = $user->fresh();

        $this->assertTrue($user->hasVerifiedEmail());

        Event::assertDispatched(Verified::class);
    }

    public function test_email_can_not_verified_with_invalid_hash()
    {
        $user = $this->createUser([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $response = $this->actingAs($user)
            ->get($verificationUrl)
            ->assertForbidden();

        $user = $user->fresh();

        $this->assertFalse($user->hasVerifiedEmail());
    }
}
