<?php

namespace Tests\Feature\Api\V1;

use App\Contracts\JsonApiContract;
use Illuminate\Testing\TestResponse;

trait JsonApiTrait
{
    /**
     * Visit the given action with a GET request, expecting a JSON response.
     *
     * @param  string  $action
     * @param  mixed  $parameters
     * @param  array  $headers
     * @return \Illuminate\Testing\TestResponse
     */
    public function getJsonApi(string $action, mixed $parameters = [], array $headers = [])
    {
        return $this->jsonApi('GET', $action, $parameters, [], $headers);
    }

    /**
     * Visit the given action with a POST request, expecting a JSON response.
     *
     * @param  string  $action
     * @param  mixed  $parameters
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Testing\TestResponse
     */
    protected function postJsonApi(string $action, mixed $parameters = [], array $data = [], array $headers = []): TestResponse
    {
        return $this->jsonApi('POST', $action, $parameters, $data, $headers);
    }

    /**
     * Visit the given action with a PUT request, expecting a JSON response.
     *
     * @param  string  $action
     * @param  mixed  $parameters
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Testing\TestResponse
     */
    public function putJsonApi(string $action, mixed $parameters = [], array $data = [], array $headers = []): TestResponse
    {
        return $this->jsonApi('PUT', $action, $parameters, $data, $headers);
    }

    /**
     * Visit the given action with a PATCH request, expecting a JSON response.
     *
     * @param  string  $action
     * @param  mixed  $parameters
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Testing\TestResponse
     */
    public function patchJsonApi(string $action, mixed $parameters = [], array $data = [], array $headers = [])
    {
        return $this->jsonApi('PATCH', $action, $parameters, $data, $headers);
    }

    /**
     * Visit the given action with a DELETE request, expecting a JSON response.
     *
     * @param  string  $action
     * @param  mixed  $parameters
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Testing\TestResponse
     */
    public function deleteJsonApi(string $action, mixed $parameters = [], array $data = [], array $headers = []): TestResponse
    {
        return $this->jsonApi('DELETE', $action, $parameters, $data, $headers);
    }

    /**
     * Call the given action with a JSON request.
     *
     * @param  string  $method
     * @param  string  $action
     * @param  mixed  $parameters
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Testing\TestResponse
     */
    protected function jsonApi(string $method, string $action, mixed $parameters = [], array $data = [], array $headers = []): TestResponse
    {
        $uri = $this->jsonApiRoute(implode('.', [static::JSON_API_RESOURCE, $action]), $parameters);

        $headers = array_merge($headers, [
            'CONTENT_TYPE' => JsonApiContract::HEADER_CONTENT_TYPE,
            'Accept' => JsonApiContract::HEADER_ACCEPT,
            JsonApiContract::HEADER_RESOURCE => static::JSON_API_RESOURCE,
        ]);

        return $this->json($method, $uri, $data, $headers);
    }

    /**
     * Generate the URL to a named route.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    protected function jsonApiRoute(string $name, mixed $parameters = [], $absolute = true): string
    {
        $version = JsonApiContract::ROUTE_API_VERSION;

        return route("api.{$version}.{$name}", $parameters, $absolute);
    }
}
