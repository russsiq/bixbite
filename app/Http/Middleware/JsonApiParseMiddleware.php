<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class JsonApiParseMiddleware extends TransformsRequest
{
    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (str_starts_with($key, 'fields')) {
            return $this->parseFields($value);
        }

        if (str_starts_with($key, 'filter')) {
            return $this->parseFilter($value);
        }

        if ('include' === $key) {
            return $this->parseInclude($value);
        }

        if (str_starts_with($key, 'page')) {
            return $this->parsePage($value);
        }

        if ('sort' === $key) {
            return $this->parseSort($value);
        }

        return $value;
    }

    protected function parseFields(?string $value): array
    {
        return $this->splitStringByComma($value);
    }

    protected function parseFilter(?string $value): string
    {
        return $value;
    }

    protected function parseInclude(?string $value): array
    {
        return $this->splitStringByComma($value);
    }

    protected function parsePage(?string $value): string
    {
        return $value;
    }

    protected function parseSort(?string $value): array
    {
        return $this->splitStringByComma($value);
    }

    protected function splitStringByComma(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        // У каждого из вновь созданных значений не должно быть боковых пробелов.
        // Метод не должен возвращать пустые вновь созданные значения.
        return preg_split(
            '/\s*,\s*/', $value, flags: PREG_SPLIT_NO_EMPTY
        );
    }
}
