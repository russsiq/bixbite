<?php

namespace Tests\Feature\App\Http\Requests\Api\V1\Article;

// Тестируемый класс.
use App\Http\Requests\Api\V1\Article\Store;

// Сторонние зависимости.
use App\Http\Requests\Api\V1\Article\ArticleRequest;
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
 * @coversDefaultClass \App\Http\Requests\Api\V1\Article\Store
 *
 * @cmd phpunit tests/Feature/App/Http/Requests/Api/V1/Article
 */
class StoreTest extends TestCase
{
    use WithFaker;

    /**
     * [$ownerUser description]
     * @var User
     */
    private $ownerUser;

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

        $this->actingAs($this->ownerUser);

        $this->app->resolving(Store::class, function (Store $resolved) {
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
                $generator($this->faker, $this->ownerUser)
            );

            $this->assertTrue($shouldPass);
        } catch (ValidationException $e) {
            $this->assertFalse($shouldPass, implode(',', array_keys($e->errors())));
        }
    }

    /**
     * Извлечь экземпляр Запроса для тестирования.
     * @param  array  $inputs
     * @return Store
     */
    protected function resolveRequestForTesting(array $inputs): Store
    {
        $this->requestingInputs = $inputs;

        return $this->app->make(Store::class);
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
                function () {
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
                function (Faker $faker, User $ownerUser) {
                    $title = $faker->sentence(mt_rand(4, 12));

                    return [
                        'user_id' => $ownerUser->id,
                        'state' => 'draft',
                        'title' => $title,
                        'slug' => Str::slug($title),
                        'allow_com' => 2,
                        'created_at' => date('Y-m-d H:i:s'),

                        // "user_id" => 51
                        // "state" => "unpublished"
                        // "title" => "Черновик"
                        // "slug" => "chernovik"
                        // "teaser" => ""
                        // "content" => ""
                        // "description" => ""
                        // "keywords" => ""
                        // "on_mainpage" => 0
                        // "is_favorite" => 0
                        // "is_pinned" => 0
                        // "is_catpinned" => 0
                        // "allow_com" => 2
                        // "created_at" => "2020-06-25 20:54:26"
                        // "tags" => []

                    ];
                },
            ],

        ];
    }
}
