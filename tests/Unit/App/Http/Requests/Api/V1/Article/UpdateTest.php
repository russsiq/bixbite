<?php

namespace Tests\Unit\App\Http\Requests\Api\V1\Article;

// Тестируемый класс.
use App\Http\Requests\Api\V1\Article\Update;

// Сторонние зависимости.
use App\Http\Requests\Api\V1\Article\ArticleRequest;
use App\Models\Article;
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
     * Таким обраом, не меняем владельца записи.
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
     * @dataProvider additionFailsDataProvider
     * @dataProvider additionPassesDataProvider
     *
     * Описание теста.
     * @param  bool  $shouldPass
     * @param  callable  $generator
     * @return void
     */
    public function testExample(bool $shouldPass, callable $generator): void
    {
        try {
            $request = $this->resolveRequestForTesting(
                $generator($this->faker)
            );

            $this->assertTrue($shouldPass);
        } catch (ValidationException $e) {
            $this->assertFalse($shouldPass);
        }
    }

    /**
     * [resolveRequestForTesting description]
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

    /**
     * [additionFailsDataProvider description]
     * @return array
     */
    public function additionFailsDataProvider(): array
    {
        return [
            'отклонить запрос из-за отсутствия данных' => [
                false,
                function (Faker $faker) {
                    return [];
                }
            ],

            'отклонить запрос из-за превышения длины заголовка' => [
                false,
                function (Faker $faker) {
                    return [
                        'title' => Str::random(256),
                    ];
                }
            ],

        ];
    }

    /**
     * [additionPassesDataProvider description]
     * @return array
     */
    public function additionPassesDataProvider(): array
    {
        return [
            'пропустить запрос с минимальным набором данных' => [
                true,
                function (Faker $faker) {
                    return [
                        'title' => $faker->sentence(mt_rand(4, 12))
                    ];
                }
            ],

        ];
    }
}
