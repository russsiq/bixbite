<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Privilege;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrivilegesController extends ApiController
{
    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->authorizeResource(Privilege::class, 'privilege');
    }

    /**
     * [index description].
     *
     * @param  Privilege  $model
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
     * [store description].
     *
     * @param  Request  $request
     * @param  Privilege  $model
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
