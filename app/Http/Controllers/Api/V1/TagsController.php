<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\Tag\CreatesTag;
use App\Contracts\Actions\Tag\DeletesTag;
use App\Contracts\Actions\Tag\FetchesTag;
use App\Contracts\Actions\Tag\UpdatesTag;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  FetchesTag  $fetcher
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(FetchesTag $fetcher, Request $request): JsonResponse
    {
        return TagCollection::make(
            $fetcher->fetchCollection($request->all())
        )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesTag  $creator
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(CreatesTag $creator, Request $request): JsonResponse
    {
        return TagResource::make(
            $creator->create($request->all())
        )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  FetchesTag  $fetcher
     * @param  Request  $request
     * @param  integer  $id
     * @return JsonResponse
     */
    public function show(FetchesTag $fetcher, Request $request, int $id): JsonResponse
    {
        return TagResource::make(
            $fetcher->fetch($id, $request->all())
        )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatesTag  $updater
     * @param  Request  $request
     * @param  Tag  $tag
     * @return JsonResponse
     */
    public function update(UpdatesTag $updater, Request $request, Tag $tag): JsonResponse
    {
        return TagResource::make(
            $updater->update($tag, $request->all())
        )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeletesTag  $deleter
     * @param  Tag  $tag
     * @return JsonResponse
     */
    public function destroy(DeletesTag $deleter, Tag $tag): JsonResponse
    {
        $deleter->delete($tag);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
