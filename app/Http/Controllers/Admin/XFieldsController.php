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
        return $this->renderOutput('index', [
            'x_fields' => $this->model->get(),
        ]);
    }

    public function create()
    {
        return $this->renderOutput('create', [
            'x_field' => [],
            'extensibles' => $this->model::extensibles(),
            'field_types' => $this->model::fieldTypes(),
        ]);
    }

    public function store(XFieldRequest $request)
    {
        $x_field = $this->model->create($request->all());
        
        return $x_field->id
            ? redirect()->route('admin.x_fields.index')->withStatus(sprintf(
                __('msg.store'), $x_field->name, $x_field->extensible
            ))
            : redirect()->back()->withInput()->withErrors(sprintf(
                __('msg.not_creating'), $x_field->name, $x_field->extensible
            ));
    }

    public function show(XField $x_field)
    {
        // Except.
    }

    public function edit(XField $x_field)
    {
        return $this->renderOutput('edit', [
            'x_field' => $x_field,
            'extensibles' => $this->model::extensibles(),
            'field_types' => $this->model::fieldTypes(),
        ]);
    }

    public function update(XFieldRequest $request, XField $x_field)
    {
        return $x_field->update($request->all())
            ? redirect()->route('admin.x_fields.index')->withStatus(sprintf(
                __('msg.update'), $x_field->name, $x_field->extensible
            ))
            : redirect()->back()->withInput()->withErrors(sprintf(
                __('msg.not_updating'), $x_field->name, $x_field->extensible
            ));
    }

    public function destroy(XField $x_field)
    {
        return $x_field->delete()
            ? redirect()->route('admin.x_fields.index')->withStatus(sprintf(
                __('msg.delete'), $x_field->name, $x_field->extensible
            ))
            : redirect()->back()->withErrors(sprintf(
                __('msg.not_deleting'), $x_field->name, $x_field->extensible
            ));
    }
}
