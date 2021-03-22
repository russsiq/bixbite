<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Contracts\JsonApiContract;
use App\Http\Middleware\JsonApiValidateMiddleware;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator as ValidationContract;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mockery;
use Mockery\Matcher\AnyArgs as AnyArgsMatcher;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Middleware\JsonApiValidateMiddleware
 *
 * @cmd vendor\bin\phpunit tests\Unit\Http\Middleware\JsonApiValidateMiddlewareTest.php
 */
class JsonApiValidateMiddlewareTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_successfully_initiated_without_query(): void
    {
        $request = Request::create('/something');

        $request->headers->set(
            'CONTENT_TYPE', JsonApiContract::HEADER_CONTENT_TYPE,
        );

        // $jsonApi = $this->app->make(JsonApi::class);
        // $translator = $this->app->make(Translator::class);
        // $validationFactory = $this->app->make(ValidationFactory::class);

        $jsonApi = Mockery::mock(JsonApiContract::class)->makePartial();
        $jsonApi->expects()
            ->setRequest($request)
            ->andReturnSelf();

        $translator = Mockery::mock(Translator::class)->makePartial();
        $validationFactory = Mockery::mock(ValidationFactory::class)->makePartial();

        $middleware = new JsonApiValidateMiddleware(
            $jsonApi, $translator, $validationFactory
        );

        $validator = Mockery::mock(ValidationContract::class)->makePartial();
        $validator->expects()->after(Mockery::on(fn ($validator) => true))->andReturnSelf();
        $validator->expects()->fails()->andReturnFalse();
        $validator->expects()->validate()->andReturn([]);

        $validationFactory->expects()->make(
            $request->all(), $middleware->rules(),
            $middleware->messages(), $middleware->attributes()
        )->andReturn($validator);

        $response = $middleware->handle($request, function (Request $actualRequest) {
            $this->assertEquals([], $actualRequest->all());
        });

        $this->assertNull($response);
    }
}

