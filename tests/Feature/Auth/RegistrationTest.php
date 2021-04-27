<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/** @cmd vendor\bin\phpunit Tests\Feature\Auth\RegistrationTest.php */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get(route('register'))
            ->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        Notification::fake();

        Event::fake([
            // Eloquent тоже инициируют события, поэтому
            // конкретизируем фальсифицированные события.
            Registered::class,
        ]);

        $response = $this->post(route('register'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'terms' => true,
            ])
            ->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertRedirect(
                RouteServiceProvider::HOME
            );

        $user = $this->assertAuthenticated()
            ->currentAuthenticatedUser();

        $this->assertInstanceOf(MustVerifyEmail::class, $user);

        $this->assertFalse($user->hasVerifiedEmail());

        Notification::assertSentTo($user, VerifyEmailNotification::class);

        Event::assertDispatchedTimes(Registered::class, 1);
    }
}
