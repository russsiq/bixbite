<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\Attachment\CreatesAttachment;
use App\Contracts\Actions\Attachment\DeletesAttachment;
use App\Contracts\Actions\Attachment\FetchesAttachment;
use App\Contracts\Actions\Attachment\UpdatesAttachment;
use App\Http\Resources\AttachmentCollection;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  FetchesAttachment  $fetcher
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(FetchesAttachment $fetcher, Request $request): JsonResponse
    {
        return AttachmentCollection::make(
                $fetcher->fetchCollection($request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesAttachment  $creator
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(CreatesAttachment $creator, Request $request): JsonResponse
    {
        return AttachmentResource::make(
                $creator->create($request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  FetchesAttachment  $fetcher
     * @param  Request  $request
     * @param  integer  $id
     * @return JsonResponse
     */
    public function show(FetchesAttachment $fetcher, Request $request, int $id): JsonResponse
    {
        return AttachmentResource::make(
                $fetcher->fetch($id, $request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatesAttachment  $updater
     * @param  Request  $request
     * @param  Attachment  $attachment
     * @return JsonResponse
     */
    public function update(UpdatesAttachment $updater, Request $request, Attachment $attachment): JsonResponse
    {
        return AttachmentResource::make(
                $updater->update($attachment, $request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeletesAttachment  $deleter
     * @param  Attachment  $attachment
     * @return JsonResponse
     */
    public function destroy(DeletesAttachment $deleter, Attachment $attachment): JsonResponse
    {
        $deleter->delete($attachment);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
