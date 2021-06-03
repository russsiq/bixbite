<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\Category\AttachesCategory;
use App\Contracts\Actions\Category\DetachesCategory;
use App\Contracts\Actions\Category\SyncsCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryableController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  AttachesCategory  $attacher
     * @param  string  $categoryable_type
     * @param  integer  $categoryable_id
     * @param  integer  $category_id
     * @return JsonResponse
     */
    public function store(
        AttachesCategory $attacher,
        string $categoryable_type,
        int $categoryable_id,
        int $category_id
    ): JsonResponse {

        $attacher->attach(
            $categoryable_type,
            $categoryable_id,
            $category_id
        );

        return response()->json(null, JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SyncsCategory  $synchronizer
     * @param  Request  $request
     * @param  string  $categoryable_type
     * @param  integer  $categoryable_id
     * @return JsonResponse
     */
    public function update(
        SyncsCategory $synchronizer,
        Request $request,
        string $categoryable_type,
        int $categoryable_id
    ): JsonResponse {

        $synchronizer->sync(
            $categoryable_type,
            $categoryable_id,
            $request->all()
        );

        return response()->json(null, JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DetachesCategory  $detacher
     * @param  string  $categoryable_type
     * @param  integer  $categoryable_id
     * @param  integer  $category_id
     * @return JsonResponse
     */
    public function destroy(
        DetachesCategory $detacher,
        string $categoryable_type,
        int $categoryable_id,
        int $category_id
    ): JsonResponse {

        $detacher->detach(
            $categoryable_type,
            $categoryable_id,
            $category_id
        );

        return response()->json(null, JsonResponse::HTTP_ACCEPTED);
    }
}
