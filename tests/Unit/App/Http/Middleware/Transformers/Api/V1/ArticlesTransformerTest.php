<?php

namespace Tests\Unit\App\Http\Middleware\Transformers\Api\V1;

// Тестируемый класс.
use App\Http\Middleware\Transformers\Api\V1\ArticlesTransformer;

// Сторонние зависимости.
use App\Support\Contracts\ResourceRequestTransformer;
use Illuminate\Http\Request;

// Библиотеки тестирования.
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Http\Middleware\Transformers\Api\V1\ArticlesTransformer
 *
 * @cmd phpunit tests\Unit\App\Http\Middleware\Transformers\Api\V1\ArticlesTransformerTest.php
 */
class ArticlesTransformerTest extends TestCase
{
    /**
     * @test
     * @dataProvider additionProvider
     *
     * [testExample description]
     * @param string $action
     * @param array  $requestingInputs
     * @param mixed  $expected
     */
    public function testExample(string $action, array $requestingInputs, $expected): void
    {
        $request = $this->createRequestWithCustomData($requestingInputs);
        $transformer = $this->createTransformer($request);

        $this->assertEquals($expected, $transformer->{$action}());
    }

    /**
     * [additionProvider description]
     * @return array
     */
    public function additionProvider(): array
    {
        return [
            'верхний регистр заголовка' => [
                'update', [
                    'title' => 'Article title',
                ], [
                    'title' => 'ARTICLE TITLE',
                ]
            ],

        ];
    }

    protected function createTransformer(Request $request)
    {
        return new ArticlesTransformer($request);
    }

    /**
     * Создать запрос с набором пользовательских данных.
     * @param  array  $requestingInputs
     * @return Request
     */
    protected function createRequestWithCustomData(array $requestingInputs): Request
    {
        $request = new Request;

        $request->merge($requestingInputs);

        return $request;
    }
}
