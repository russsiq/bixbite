<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Privilege;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrivilegesController extends ApiController
{
    public function __construct()
    {
        $this->authorizeResource(Privilege::class, 'privilege');
    }

    public function index(Privilege $model)
    {
        $roles = array_values($model->roles());
        $privileges = $model->get();

        return response()
            ->json(compact('roles', 'privileges'))
            ->setStatusCode(Response::HTTP_OK);
    }

    public function store(Request $request, Privilege $model)
    {
        $roles = array_values($model->roles());
        $model->saveTable($request->only($roles));
        $privileges = $model->get();

        return response()
            ->json(compact('roles', 'privileges'))
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
