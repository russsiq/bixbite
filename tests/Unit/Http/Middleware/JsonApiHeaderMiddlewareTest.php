<?php

namespace Tests\Unit\Http\Middleware;

use App\Contracts\JsonApiContract;
use App\Http\Middleware\JsonApiHeaderMiddleware;
use App\Support\JsonApi;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Http\Middleware\JsonApiHeaderMiddleware
 *
 * @cmd vendor\bin\phpunit tests\Unit\Http\Middleware\JsonApiHeaderMiddlewareTest.php
 */
class JsonApiHeaderMiddlewareTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_successfully_initiated(): void
    {
        $request = Mockery::mock(Request::class);

        $request->shouldReceive('header')
            ->times(3)
            ->andReturnUsing(function (string $argument) {
                $headers = [
                    'ACCEPT' => JsonApiContract::HEADER_ACCEPT,
                    'CONTENT_TYPE' => JsonApiContract::HEADER_CONTENT_TYPE,
                    JsonApiContract::HEADER_RESOURCE => head(JsonApiContract::RESORCE_TO_MODEL_MAP)::TABLE,
                ];

                $this->assertArrayHasKey($argument, $headers);

                return $headers[$argument];
            });

        $jsonApi = Mockery::mock(JsonApi::class);

        $jsonApi->shouldReceive('setRequest')
            ->with($request)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('isApiUrl')
            ->withNoArgs()
            ->once()
            ->andReturnTrue();

        $middleware = new JsonApiHeaderMiddleware($jsonApi);

        $response = $middleware->handle($request, function () {});

        $this->assertEquals($response, null);
    }
}
