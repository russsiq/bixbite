<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\Note\CreatesNote;
use App\Contracts\Actions\Note\DeletesNote;
use App\Contracts\Actions\Note\FetchesNote;
use App\Contracts\Actions\Note\UpdatesNote;
use App\Http\Resources\NoteCollection;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  FetchesNote  $fetcher
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(FetchesNote $fetcher, Request $request): JsonResponse
    {
        return NoteCollection::make(
                $fetcher->fetchCollection($request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesNote  $creator
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(CreatesNote $creator, Request $request): JsonResponse
    {
        return NoteResource::make(
                $creator->create($request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  FetchesNote  $fetcher
     * @param  Request  $request
     * @param  integer  $id
     * @return JsonResponse
     */
    public function show(FetchesNote $fetcher, Request $request, int $id): JsonResponse
    {
        return NoteResource::make(
                $fetcher->fetch($id, $request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatesNote  $updater
     * @param  Request  $request
     * @param  Note  $note
     * @return JsonResponse
     */
    public function update(UpdatesNote $updater, Request $request, Note $note): JsonResponse
    {
        return NoteResource::make(
                $updater->update($note, $request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeletesNote  $deleter
     * @param  Note  $note
     * @return JsonResponse
     */
    public function destroy(DeletesNote $deleter, Note $note): JsonResponse
    {
        $deleter->delete($note);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
