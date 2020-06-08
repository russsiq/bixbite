<?php

namespace Tests\Unit\App\Http\Middleware;

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
     * @covers ::__construct
     *
     * Успешная инициализация посредника.
     * @return void
     */
    public function testSuccessfullyInitiated(): void
    {
        $middleware = $this->createMiddleware('api.someResource.someMethod');

        $this->assertSame('api.someResource.someMethod', $middleware->currentRouteName());
        $this->assertSame('api', $middleware->group());
        $this->assertSame('someResource', $middleware->resource());
        $this->assertSame('someMethod', $middleware->action());
    }

    /**
     * @test
     * @covers ::hasTransformerForCurrentRoute
     *
     * Запрос текущего маршрута имеет преобразователь данных.
     * @return void
     */
    public function testRequestForCurrentRouteHasDataTransformer(): void
    {
        $transformers = TransformApiData::AVAILABLE_TRANSFORMERS;
        $resources = array_keys($transformers);
        $resource = array_pop($resources);

        $actions = TransformApiData::ALLOWED_ACTIONS;
        $action = array_pop($actions);

        $middleware = $this->createMiddleware("api.{$resource}.{$action}");

        $this->assertTrue($middleware->hasTransformerForCurrentRoute());
    }

    /**
     * @test
     * @covers ::hasTransformerForCurrentRoute
     *
     * Запрос текущего маршрута не имеет преобразователя данных.
     * @return void
     */
    public function testRequestForCurrentRouteDoesNotHaveDataTransformer(): void
    {
        $middleware = $this->createMiddleware('api.someResource.someMethod');

        $this->assertFalse($middleware->hasTransformerForCurrentRoute());
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
        $this->createMiddleware($currentRouteName)
            ->handle(
                $this->createRequestWithCustomData($requestingInputs),
                function (Request $transformed) use ($expected) {
                    $this->assertEquals($expected, $transformed->title);
                }
            );

        // // Остановиться тут и отметить, что тест неполный.
        // $this->markTestIncomplete(
        //     'Этот тест ещё не реализован.'
        // );
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
    protected function createMiddleware(string $routeName): TransformApiData
    {
        Route::shouldReceive('currentRouteName')
            ->once()
            ->andReturn($routeName);

        return new TransformApiData;
    }
}
