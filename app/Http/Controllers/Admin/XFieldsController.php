<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\XField;
use BBCMS\Http\Requests\Admin\XFieldRequest;
use BBCMS\Http\Controllers\Admin\AdminController;

class XFieldsController extends AdminController
{
    protected $model;
    protected $template = 'x_fields';

    public function __construct(XField $model)
    {
        parent::__construct();

        $this->model = $model;
    }

    public function index()
    {
        $x_fields = $this->model->get();
        
        return $this->makeResponse('index', compact('x_fields'));
    }

    public function create()
    {
        return $this->makeResponse('create', [
            'x_field' => [],
            'extensibles' => $this->model::extensibles(),
            'field_types' => $this->model::fieldTypes(),
        ]);
    }

    public function store(XFieldRequest $request)
    {
        $x_field = $this->model->create($request->all());

        return $this->makeRedirect(true, 'admin.x_fields.index', sprintf(
            __('msg.store'), $x_field->name, $x_field->extensible
        ));
    }

    public function edit(XField $x_field)
    {
        return $this->makeResponse('edit', [
            'x_field' => $x_field,
            'extensibles' => $this->model::extensibles(),
            'field_types' => $this->model::fieldTypes(),
        ]);
    }

    public function update(XFieldRequest $request, XField $x_field)
    {
        $x_field->update($request->all());

        return $this->makeRedirect(true, 'admin.x_fields.index', sprintf(
            __('msg.update'), $x_field->name, $x_field->extensible
        ));
    }

    public function destroy(XField $x_field)
    {
        $x_field->delete();

        return $this->makeRedirect(true, 'admin.x_fields.index',  __('msg.destroy'));
    }
}
