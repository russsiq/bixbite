<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\Comment\CreatesComment;
use App\Contracts\Actions\Comment\DeletesComment;
use App\Contracts\Actions\Comment\FetchesComment;
use App\Contracts\Actions\Comment\MassUpdatesComment;
use App\Contracts\Actions\Comment\UpdatesComment;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  FetchesComment  $fetcher
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(FetchesComment $fetcher, Request $request): JsonResponse
    {
        return CommentCollection::make(
                $fetcher->fetchCollection($request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  FetchesComment  $fetcher
     * @param  Request  $request
     * @param  integer  $id
     * @return JsonResponse
     */
    public function show(FetchesComment $fetcher, Request $request, int $id): JsonResponse
    {
        return CommentResource::make(
                $fetcher->fetch($id, $request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatesComment  $updater
     * @param  Request  $request
     * @param  Comment  $comment
     * @return JsonResponse
     */
    public function update(UpdatesComment $updater, Request $request, Comment $comment): JsonResponse
    {
        return CommentResource::make(
                $updater->update($comment, $request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Update the specified resource collection in storage.
     *
     * @param  MassUpdatesComment  $updater
     * @param  Request  $request
     * @return JsonResponse
     */
    public function massUpdate(MassUpdatesComment $updater, Request $request): JsonResponse
    {
        return CommentResource::collection(
                $updater->massUpdate($request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeletesComment  $deleter
     * @param  Comment  $comment
     * @return JsonResponse
     */
    public function destroy(DeletesComment $deleter, Comment $comment): JsonResponse
    {
        $deleter->delete($comment);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
