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
