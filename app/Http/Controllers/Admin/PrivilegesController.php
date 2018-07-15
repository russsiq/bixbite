<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Privilege;
use BBCMS\Http\Requests\Admin\PrivilegeRequest;
use BBCMS\Http\Requests\Admin\PrivilegesRequest;
use BBCMS\Http\Controllers\Admin\AdminController;

class PrivilegesController extends AdminController
{
    protected $roles;
    protected $privileges;
    protected $template = 'privileges';

    public function __construct(Privilege  $model)
    {
        parent::__construct();
        $this->authorizeResource(Privilege::class);

        $this->roles = $model->roles();
        $this->privileges = $model->get();
    }

    public function index()
    {
        $this->authorize(Privilege::class);

        return $this->renderOutput('index', [
            'roles' => $this->roles,
            'privileges' => $this->privileges
        ]);
    }

    public function create()
    {
        //
    }

    public function store(PrivilegeRequest $request)
    {
        //
    }

    public function show(Privilege $privilege)
    {
        //
    }

    public function edit(Privilege $privilege)
    {
        //
    }

    public function update(PrivilegeRequest $request, Privilege $privilege)
    {
        //
    }

    public function massUpdate(PrivilegesRequest $request, Privilege $privileges)
    {
        $this->authorize('otherUpdate', Privilege::class);
        $privileges->saveTable($request->except(['_token', 'created_at', 'submit']));

        return redirect()->route('admin.privileges.index')->withStatus('store!');
    }

    public function destroy(Privilege $privileges)
    {
        //
    }
}
