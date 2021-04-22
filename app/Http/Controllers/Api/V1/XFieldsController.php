<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\XField\Store as StoreXFieldRequest;
use App\Http\Requests\Api\V1\XField\Update as UpdateXFieldRequest;
use App\Http\Resources\XFieldCollection;
use App\Http\Resources\XFieldResource;
use App\Models\XField;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class XFieldsController extends ApiController
{
    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->authorizeResource(XField::class, 'x_field');
    }

    /**
     * Отобразить список сущностей с дополнительной фильтрацией.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $x_fields = XField::with([

        ])
            ->withCount([

            ])
            ->advancedFilter();

        $collection = new XFieldCollection($x_fields);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     *
     * @param  StoreXFieldRequest  $request
     * @return JsonResponse
     */
    public function store(StoreXFieldRequest $request)
    {
        $x_field = XField::create($request->all());

        $resource = new XFieldResource($x_field);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
     *
     * @param  XField  $x_field
     * @return JsonResponse
     */
    public function show(XField $x_field)
    {
        $x_field->load([

        ]);

        $resource = new XFieldResource($x_field);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     *
     * @param  UpdateXFieldRequest  $request
     * @param  XField  $x_field
     * @return JsonResponse
     */
    public function update(UpdateXFieldRequest $request, XField $x_field)
    {
        $x_field->update($request->all());

        $resource = new XFieldResource($x_field);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить сущность из хранилища.
     *
     * @param  Request  $request
     * @param  XField  $x_field
     * @return JsonResponse
     */
    public function destroy(Request $request, XField $x_field)
    {
        // Check if currently authenticated user has owner role.
        if ($request->user()->hasRole('owner')) {
            $x_field->delete();

            return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return response()
            ->json([
                'message' => 'You can not delete x_fields.',
            ], JsonResponse::HTTP_FORBIDDEN);
    }
}
