<?php

namespace Tests\Unit\App\Services;

// Тестируемый класс.
use App\Http\Middleware\TransformApiData;

// Сторонние зависимости.
use App\Support\Contracts\ResourceRequestTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Библиотеки тестирования.
use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Http\Middleware\TransformApiData
 *
 * @cmd phpunit tests\Unit\App\Http\Middleware\TransformApiDataTest.php
 */
class TransformApiDataTest extends TestCase
{
    protected $mocks = [];

    protected function tearDown(): void
    {
        m::close();

        $this->mocks = [];
    }

    /**
     * @test
     * @dataProvider additionProvider
     *
     * [testExample description]
     * @param string $currentRouteName
     * @param array  $requestingInputs
     * @param [type] $expected
     */
    public function testExample(string $currentRouteName, array $requestingInputs, $expected): void
    {
        Route::shouldReceive('currentRouteName')
            ->once()
            ->andReturn($currentRouteName);

        $this->createMiddleware()
            ->handle(
                $this->createRequestWithCustomData($requestingInputs),
                function (Request $transformed) use ($expected) {
                    $this->assertEquals($expected, $transformed->title);
                }
            );

        // Остановиться тут и отметить, что тест неполный.
        $this->markTestIncomplete(
            'Этот тест ещё не реализован.'
        );
    }

    /**
     * [additionProvider description]
     * @return array
     */
    public function additionProvider()
    {
        return [
            'верхний регистр заголовка' => [
                'api.articles.update', [
                    'title' => 'Article title',
                ], 'ARTICLE TITLE'],

        ];
    }

    /**
     * [createRequestWithCustomData description]
     * @param  array  $requestingInputs
     * @return Request
     */
    protected function createRequestWithCustomData(array $requestingInputs): Request
    {
        $request = new Request;

        $request->merge($requestingInputs);

        return $request;
    }

    /**
     * [createMiddleware description]
     * @return TransformApiData
     */
    protected function createMiddleware(): TransformApiData
    {
        return new TransformApiData;
    }
}
