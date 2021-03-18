<?php

namespace App\Exceptions;

use App\Support\JsonApi;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class JsonApiException extends HttpResponseException
{
    /**
     * Create new Http Response Exception.
     *
     * @param  int  $status
     * @param  array  $errors
     * @param  array  $headers
     * @return HttpResponseException
     */
    public static function make(
        int $status = JsonResponse::HTTP_BAD_REQUEST,
        array $errors = [],
        array $headers = []
    ): HttpResponseException {

        foreach ($errors as $key => $error) {
            $errors[$key]['status'] = $error['status'] ?? $status;
            $errors[$key]['title'] = $error['title'] ?? JsonResponse::$statusTexts[$status];
        }

        return new static(
            new JsonResponse(
                [
                    'jsonapi' => [
                        'version' => JsonApi::SPEC_VERSION,
                    ],
                    'errors' => $errors,
                ],
                $status,
                array_merge($headers, [
                    'Content-Type' => JsonApi::HEADER_CONTENT_TYPE,
                ])
            )
        );
    }

    /**
     * Create new Http Response Exception from Validator instance.
     *
     * @param  Validator $validator
     * @param  int  $status
     * @return HttpResponseException
     */
    public static function makeFromValidator(
        Validator $validator,
        string $sourceMember = 'pointer',
        int $status = JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
        string $statusText = null
    ): HttpResponseException {
        $statusText = $statusText ?: JsonResponse::$statusTexts[$status];

        $errors = [];

        foreach ($validator->errors()->messages() as $key => $messages) {
            foreach ($messages as $message) {
                $errors[] = [
                    'status' => $status,
                    'title' => $statusText,
                    'detail' => $message,
                    'source' => [
                        $sourceMember => '/'.str_replace('.', '/', $key),
                    ],
                    'meta' => [
                        'key' => $key,
                    ],
                ];
            }
        }

        return static::make($status, $errors);
    }
}
