<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\Tag\AttachesTag;
use App\Contracts\Actions\Tag\DetachesTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class TaggableController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  AttachesTag  $attacher
     * @param  string  $taggable_type
     * @param  integer  $taggable_id
     * @param  integer  $tag_id
     * @return JsonResponse
     */
    public function store(
        AttachesTag $attacher,
        string $taggable_type,
        int $taggable_id,
        int $tag_id
    ): JsonResponse {

        $attacher->attach(
            $taggable_type,
            $taggable_id,
            $tag_id
        );

        return response()->json(null, JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DetachesTag  $detacher
     * @param  string  $taggable_type
     * @param  integer  $taggable_id
     * @param  integer  $tag_id
     * @return JsonResponse
     */
    public function destroy(
        DetachesTag $detacher,
        string $taggable_type,
        int $taggable_id,
        int $tag_id
    ): JsonResponse {

        $detacher->detach(
            $taggable_type,
            $taggable_id,
            $tag_id
        );

        return response()->json(null, JsonResponse::HTTP_ACCEPTED);
    }
}
