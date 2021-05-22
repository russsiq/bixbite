<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\XField\CreatesXField;
use App\Contracts\Actions\XField\DeletesXField;
use App\Contracts\Actions\XField\FetchesXField;
use App\Contracts\Actions\XField\UpdatesXField;
use App\Http\Resources\XFieldCollection;
use App\Http\Resources\XFieldResource;
use App\Models\XField;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class XFieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  FetchesXField  $fetcher
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(FetchesXField $fetcher, Request $request): JsonResponse
    {
        return XFieldCollection::make(
                $fetcher->fetchCollection($request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesXField  $creator
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(CreatesXField $creator, Request $request): JsonResponse
    {
        return XFieldResource::make(
                $creator->create($request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  FetchesXField  $fetcher
     * @param  Request  $request
     * @param  integer  $id
     * @return JsonResponse
     */
    public function show(FetchesXField $fetcher, Request $request, int $id): JsonResponse
    {
        return XFieldResource::make(
                $fetcher->fetch($id, $request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatesXField  $updater
     * @param  Request  $request
     * @param  XField  $x_field
     * @return JsonResponse
     */
    public function update(UpdatesXField $updater, Request $request, XField $x_field): JsonResponse
    {
        return XFieldResource::make(
                $updater->update($x_field, $request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeletesXField  $deleter
     * @param  XField  $x_field
     * @return JsonResponse
     */
    public function destroy(DeletesXField $deleter, XField $x_field): JsonResponse
    {
        $deleter->delete($x_field);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
