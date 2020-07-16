<?php declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers;

// Тестируемый класс.
use App\Http\Controllers\HomeController;

// Сторонние зависимости.
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\HomeController
 *
 * @cmd vendor\bin\phpunit tests\Feature\App\Http\Controllers\HomeControllerWithoutContentTest.php
 */
class HomeControllerWithoutContentTest extends TestCase
{
    // Обновление миграций единожды при выполнении
    // всего комплекса тестов для `физической` (не `:memory:`) БД.
    // Перед каждым тестом (при выполнении `setUp`): запись транзакций.
    // После каждого теста (при выполнении`tearDown`): все транзакции будут откатаны.
    use RefreshDatabase;

    /**
     * @test
     * @covers ::index
     *
     * Гость можеть просмотреть домашнюю страницу.
     * @return void
     */
    public function testGuestCanBrowseHomePage(): void
    {
        $this->get(route('home'))
            ->assertStatus(200);
    }

    /**
     * @test
     * @covers ::index
     *
     * Пользователь можеть просмотреть домашнюю страницу.
     * @return void
     */
    public function testUserCanBrowseHomePage(): void
    {
        $this->actingAs(
                factory(User::class)
                    ->create()
            )
            ->get(route('home'))
            ->assertStatus(200);
    }
}
