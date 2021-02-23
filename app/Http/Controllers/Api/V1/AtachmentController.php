<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Atachment\IndexAtachmentRequest;
use App\Http\Requests\Api\V1\Atachment\StoreAtachmentRequest;
use App\Http\Requests\Api\V1\Atachment\UpdateAtachmentRequest;
use App\Http\Resources\AtachmentCollection;
use App\Http\Resources\AtachmentResource;
use App\Models\Atachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AtachmentController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Atachment::class, 'atachment');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexAtachmentRequest $request)
    {
        $atachments = Atachment::with([
            //
        ])->paginate();

        $collection = new AtachmentCollection($atachments);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreAtachmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAtachmentRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        /** @var \App\Models\Atachment $atachmentInstance */
        $atachment = $user->atachments()
            ->create($request->validated());

        $resource = new AtachmentResource(
            $atachment->refresh()
        );

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Atachment  $atachment
     * @return \Illuminate\Http\Response
     */
    public function show(Atachment $atachment)
    {
        $resource = new AtachmentResource($atachment);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Atachment  $atachment
     * @return \Illuminate\Http\Response
     */
    public function edit(Atachment $atachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateAtachmentRequest  $request
     * @param  \App\Models\Atachment  $atachment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAtachmentRequest $request, Atachment $atachment)
    {
        $atachment->update(
            $request->validated()
        );

        $resource = new AtachmentResource(
            $atachment->refresh()
        );

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Atachment  $atachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Atachment $atachment)
    {
        $atachment->delete();

        return response()
            ->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
