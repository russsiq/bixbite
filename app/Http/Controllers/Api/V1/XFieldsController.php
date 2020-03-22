<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\XField;

use App\Http\Resources\XFieldResource;
use App\Http\Resources\XFieldCollection;

use App\Http\Requests\Api\V1\XField\Store as StoreXFieldRequest;
use App\Http\Requests\Api\V1\XField\Update as UpdateXFieldRequest;

use Illuminate\Http\JsonResponse;

class XFieldsController extends ApiController
{
    public function __construct()
    {
        $this->authorizeResource(XField::class, 'x_field');
    }

    /**
     * Отобразить список сущностей с дополнительной фильтрацией.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $x_fields = XField::with([
                //
            ])
            ->withCount([
                //
            ])
            ->advancedFilter();

        $collection = new XFieldCollection($x_fields);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     * @param  StoreXFieldRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreXFieldRequest $request)
    {
        $x_field = XField::create($request->all());

       $resource = new XFieldResource($x_field);

       return $resource->response()
           ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  XField  $x_field
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(XField $x_field)
    {
        $x_field->load([
            //
        ]);

        $resource = new XFieldResource($x_field);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     * @param  UpdateXFieldRequest  $request
     * @param  XField  $x_field
     * @return \Illuminate\Http\JsonResponse
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
     * @param  XField  $x_field
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(XField $x_field)
    {
        // Check if currently authenticated user has owner role.
        if (auth('api')->user()->hasRole('owner')) {
            $x_field->delete();

            return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return response()->json([
                'message' => 'You can not delete x_fields.'
            ], JsonResponse::HTTP_FORBIDDEN);
    }
}
