<?php

namespace Tests\Unit\App\Mixins;

// Тестируемый класс.
use App\Mixins\StrMixin;

// Сторонние зависимости.
use Illuminate\Support\Str;

// Библиотеки тестирования.
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Mixins\StrMixin
 *
 * @cmd vendor\bin\phpunit tests\Unit\App\Mixins\StrMixinTest.php
 */
class StrMixinTest extends TestCase
{
    protected function setUp(): void
    {

    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
