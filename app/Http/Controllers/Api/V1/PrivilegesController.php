<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Privilege;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PrivilegesController extends ApiController
{
    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->authorizeResource(Privilege::class, 'privilege');
    }

    /**
     * [index description]
     * @param  Privilege    $model
     * @return JsonResponse
     */
    public function index(Privilege $model): JsonResponse
    {
        $roles = array_values($model->roles());
        $privileges = $model->get();

        return response()
            ->json([
                'data' => compact('roles', 'privileges'),

            ])
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * [store description]
     * @param  Request      $request
     * @param  Privilege    $model
     * @return JsonResponse
     */
    public function store(Request $request, Privilege $model): JsonResponse
    {
        $roles = array_values($model->roles());
        $model->saveTable($request->only($roles));
        $privileges = $model->get();

        return response()
            ->json([
                'data' => compact('roles', 'privileges'),

            ])
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }
}
