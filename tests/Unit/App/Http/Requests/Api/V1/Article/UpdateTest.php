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
}
