<?php

namespace Tests\Unit\App\Http\Middleware\Transformers\Api\V1;

// Тестируемый класс.
use App\Http\Middleware\Transformers\Api\V1\ArticlesTransformer;

// Сторонние зависимости.
use App\Support\Contracts\ResourceRequestTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @covers ::store
     *
     * Обязательное присутствие идентификатора пользователя в списке полей ввода.
     * Таким образом, система сама задаёт владельца записи.
     * @return void
     */
    public function testIncludedUserIdWhenStoreArticle(): void
    {
        // Не доверяя пользователю,
        // выбираем его идентификатор
        // из фасада аутентификации.
        Auth::shouldReceive('id')
            ->once()
            ->andReturn($user_id = mt_rand(1, 512));

        $request = $this->createRequestWithCustomData([
            'title' => 'Some title',

        ]);

        $transformer = $this->createTransformer($request);
        $transformed = $transformer->store();

        $this->assertEquals($user_id, $transformed['user_id']);
    }

    /**
     * @test
     * @covers ::update
     *
     * Исключение идентификатора пользователя из списка полей ввода.
     * Таким образом, не меняем владельца записи.
     * @return void
     */
    public function testExceptedUserIdWhenUpdateArticle(): void
    {
        $request = $this->createRequestWithCustomData([
            'title' => 'Some title',
            'user_id' => mt_rand(1, 512),

        ]);

        $transformer = $this->createTransformer($request);
        $transformed = $transformer->update();

        $this->assertArrayNotHasKey('user_id', $transformed);
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
