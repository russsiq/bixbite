<?php

namespace BBCMS\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{

    /**
     * Validate the input.
     *
     * @param  \Illuminate\Validation\Factory  $factory
     * @return \Illuminate\Validation\Validator
     */
    public function validator($factory)
    {
        if (method_exists($this, 'sanitize')) {
            $this->merge($this->container->call([$this, 'sanitize']));
        }

        return $factory->make(
            $this->validationData(), $this->container->call([$this, 'rules']),
            $this->messages(), $this->attributes()
        );
    }

    // Override the forbiddenResponse() method
    /*public function forbiddenResponse()
    {
        // return response()->json(["message" => "This action is unauthorized."], 403);
        // return new JsonResponse('Unauthorized', 403);
        // or return Response::make('Unauthorized', 403);
        // return response()->view('errors.403');
    }*/
}
