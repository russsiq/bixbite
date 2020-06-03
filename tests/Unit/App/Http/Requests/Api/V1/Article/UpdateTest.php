<?php

namespace Tests\Unit\App\Http\Requests\Api\V1\Article;

// Тестируемый класс.
use App\Http\Requests\Api\V1\Article\Update;

// Сторонние зависимости.
use App\Http\Requests\Api\V1\Article\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Requests\Api\V1\Article\Update
 *
 * В данном классе по-минимуму будет использован `Faker`.
 * Дабы избежать дублирования кода с `StoreTest`,
 * так как у них один родительский класс.
 * @cmd phpunit tests/Unit/App/Http/Requests/Api/V1/Article
 */
class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * [$ownerUser description]
     * @var User
     */
    private $ownerUser;

    /**
     * [$article description]
     * @var Article
     */
    private $article;

    /**
     * [$requestingInputs description]
     * @var array
     */
    private $requestingInputs = [];

    /**
     * [$faker description]
     * @var Faker
     */
    protected $faker;

    /**
     * [setUp description]
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->ownerUser = factory(User::class)
            ->states('owner')
            ->create();

        $this->article = factory(Article::class)
            ->create([
                'user_id' => $this->ownerUser->id
            ]);

        $this->actingAs($this->ownerUser);

        $this->app->resolving(Update::class, function (Update $resolved) {
            $resolved->replace($this->requestingInputs);
        });
    }

    /**
     * @test
     *
     * Прямая зависимость от родительского класса.
     * @return void
     */
    public function testSuccessfullyInitiated(): void
    {
        $this->expectException(ValidationException::class);
        $request = $this->resolveRequestForTesting([]);
        $this->assertInstanceOf(ArticleRequest::class, $request);
    }

    /**
     * @test
     *
     * Исключение идентификатора пользователя из списка полей ввода.
     * Таким образом, не меняем владельца записи.
     * @return void
     */
    public function testExceptedUserId(): void
    {
        $request = $this->resolveRequestForTesting([
            'title' => 'Some title',
            'user_id' => $this->ownerUser->id,

        ]);

        $this->assertArrayNotHasKey('user_id', $request->all());
    }

    /**
     * @test
     *
     * Сброс статуса Записи, если не указаны категории.
     * @return void
     */
    public function testResetStateArticleWithoutCategoriesToUnpublished(): void
    {
        $request = $this->resolveRequestForTesting([
            'title' => 'Some title',
            'categories' => [],
            'state' => 'published',

        ]);

        $this->assertSame('unpublished', $request->get('state'));
    }

    /**
     * @test
     *
     * Сброс статуса Записи, если статус не указан.
     * @return void
     */
    public function testResetStateArticleWithoutStateToUnpublished(): void
    {
        $request = $this->resolveRequestForTesting([
            'title' => 'Some title',

        ]);

        $this->assertSame('unpublished', $request->get('state'));
    }

    /**
     * @test
     *
     * Статус Записи не изменен, если указаны необходимые данные.
     * @return void
     */
    public function testNotChangedArticleStateWithNecessaryData(): void
    {
        $categories = factory(Category::class, 3)
            ->create()
            ->modelKeys();

        $request = $this->resolveRequestForTesting([
            'title' => 'Some title',
            'categories' => $categories,
            'state' => 'published',

        ]);

        $this->assertSame('published', $request->get('state'));
    }

    /**
     * Извлечь экземпляр Запроса для тестирования.
     * @param  array  $inputs
     * @return Update
     */
    protected function resolveRequestForTesting(array $inputs): Update
    {
        // При использовании API-запроса, всегда передаётся идентификатор.
        $inputs['id'] = $this->article->id;

        $this->requestingInputs = $inputs;

        return $this->app->make(Update::class);
    }
}
