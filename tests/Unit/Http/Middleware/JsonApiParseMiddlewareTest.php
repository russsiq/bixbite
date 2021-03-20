<?php

namespace Tests\Unit\Http\Middleware;

use App\Contracts\JsonApiContract;
use App\Http\Middleware\JsonApiParseMiddleware;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Http\Middleware\JsonApiParseMiddleware
 *
 * @cmd vendor\bin\phpunit tests\Unit\Http\Middleware\JsonApiParseMiddlewareTest.php
 */
class JsonApiParseMiddlewareTest extends TestCase
{
    public function test_successfully_initiated(): void
    {
        $request = Request::create(
            '?include=user,comments.user, comments . user . atachments , null'.
            '&fields[articles]=title, body&fields[user]=name'.
            '&filter[0][column]= title&filter[0][operator]=contains&filter[0][query_1]=ipsum'.
            '&filter[match]=or'.
            '&sort=-created, title , user . name'.
            '&page[number]=1&page[size]=8'
        );

        $request->headers->set(
            'CONTENT_TYPE', JsonApiContract::HEADER_CONTENT_TYPE,
        );

        $response = (new JsonApiParseMiddleware)->handle($request, function (Request $expectedRequest) {
            $this->assertEquals($expectedRequest->all(), [
                'include' => ['user', 'comments.user', 'comments.user.atachments', 'null'],
                'fields' => [
                    'articles' => ['title', 'body'],
                    'user' => ['name'],
                ],
                'filter' => [
                    ['column' => 'title', 'operator' => 'contains', 'query_1' => 'ipsum'],
                    'match' => 'or',
                ],
                'sort' => ['-created', 'title', 'user.name'],
                'page' => [
                    'number' => 1,
                    'size' => 8,
                ],
            ]);
        });

        $this->assertEquals($response, null);
    }
}
