<?php

declare(strict_types=1);

namespace Tests\Feature\View\Components;

use App\View\Components\ConsentCookie;
use Tests\TestCase;

/**
 * @cmd vendor/bin/phpunit Tests\Feature\View\Components\ConsentCookieTest.php
 */
class ConsentCookieTest extends TestCase
{
    public function test_user_can_view_consent(): void
    {
        $this->get(route('home'));

        $component = $this->resolveComponentForTesting();

        $this->assertTrue($component->shouldRender());
    }

    public function test_user_cannot_view_consent_when_accepted(): void
    {
        $this->disableCookieEncryption()
            ->withCookie(
                ConsentCookie::COOKIE_NAME,
                ConsentCookie::COOKIE_VALUE
            )
            ->get(route('home'));

        $component = $this->resolveComponentForTesting();

        $this->assertFalse($component->shouldRender());
    }

    protected function resolveComponentForTesting(): ConsentCookie
    {
        return $this->app->make(ConsentCookie::class);
    }
}
