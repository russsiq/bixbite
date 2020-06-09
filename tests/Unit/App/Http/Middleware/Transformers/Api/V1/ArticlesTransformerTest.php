<?php

namespace Tests\Unit\App\Http\Middleware\Transformers\Api\V1;

// Тестируемый класс.
use App\Http\Middleware\Transformers\Api\V1\ArticlesTransformer;

// Сторонние зависимости.
use App\Support\Contracts\ResourceRequestTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

    /**
     * @test
     * @covers ::default
     *
     * Сброс статуса Записи, если не указаны категории.
     * @return void
     */
    public function testResetStateArticleWithoutCategoriesToUnpublished(): void
    {
        $request = $this->createRequestWithCustomData([
            'title' => 'Some title',
            'categories' => [],
            'state' => 'published',

        ]);

        $transformer = $this->createTransformer($request);
        $transformed = $transformer->default();

        $this->assertSame('unpublished', $transformed['state']);
    }

    /**
     * @test
     * @covers ::default
     *
     * Сброс статуса Записи, если статус не указан.
     * @return void
     */
    public function testResetStateArticleWithoutStateToUnpublished(): void
    {
        $request = $this->createRequestWithCustomData([
            'title' => 'Some title',

        ]);

        $transformer = $this->createTransformer($request);
        $transformed = $transformer->default();

        $this->assertSame('unpublished', $transformed['state']);
    }

    /**
     * @test
     * @covers ::default
     *
     * Статус Записи не изменен, если указаны необходимые данные.
     * @return void
     */
    public function testNotChangedArticleStateWithNecessaryData(): void
    {
        $request = $this->createRequestWithCustomData([
            'title' => 'Some title',
            'categories' => [1, 2, 3],
            'state' => 'published',

        ]);

        $transformer = $this->createTransformer($request);
        $transformed = $transformer->default();

        $this->assertSame('published', $transformed['state']);
    }

    /**
     * @test
     * @covers ::default
     *
     * SEO-поля Записи с типом `string` обрезаются до 255 символов.
     * Тип в БД `varchar(255)`.
     * @return void
     */
    public function testSeoFieldsWithStringTypeTrimmedTo255Characters(): void
    {
        // Массив тестируемых полей. Прописываем вручную,
        // так как ожидаем определенного поведения от системы,
        // а не тестируем уже сформированное поведение,
        // описанное в методе `rules`.
        $fields = [
            'slug',
            // 'teaser',
            'description',
            'keywords',
            // 'tags.*',

        ];

        $filledFields = array_fill_keys($fields, Str::random(256));

        $request = $this->createRequestWithCustomData(array_merge($filledFields, [
            'title' => 'Some title',

        ]));

        $transformer = $this->createTransformer($request);
        $transformed = $transformer->default();

        foreach ($fields as $field) {
            $this->assertLessThanOrEqual(255, $transformed[$field]);
        }
    }

    protected function createTransformer(Request $request): ResourceRequestTransformer
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
