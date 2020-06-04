<?php

namespace Tests\Feature\App\View\Components;

// Тестируемый класс.
use App\View\Components\ConsentCookie;

// Сторонние зависимости.
use Tests\TestCase;

/**
 * @coversDefaultClass \App\View\Components\ConsentCookie
 */
class ConsentCookieTest extends TestCase
{
    /**
     * @test
     * @covers ::shouldRender
     *
     * Пользователь можеть видеть панель об использовании кук на сайте.
     * @return void
     */
    public function testUserCanViewConsent(): void
    {
        $this->get(route('home'));

        $component = $this->resolveComponentForTesting();

        $this->assertTrue($component->shouldRender());
    }

    /**
     * @test
     * @covers ::shouldRender
     *
     * Пользователь не можеть видеть панель об использовании кук на сайте,
     * когда он уже дал свое согласие на их обработку.
     * @return void
     */
    public function testUserCanNotViewConsentWhenAccepted(): void
    {
        $this->disableCookieEncryption()
            ->withCookie(
                ConsentCookie::ACCEPTED_NAME,
                ConsentCookie::ACCEPTED_VALUE
            )
            ->get(route('home'));

        $component = $this->resolveComponentForTesting();

        $this->assertFalse($component->shouldRender());
    }

    /**
     * Извлечь экземпляр Компонента для тестирования.
     * @return ConsentCookie
     */
    protected function resolveComponentForTesting(): ConsentCookie
    {
        return $this->app->make(ConsentCookie::class);
    }
}
