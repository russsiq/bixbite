<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Privilege;

use Illuminate\Http\Request;

class PrivilegesController extends AdminController
{
    protected $roles;
    protected $privileges;
    protected $template = 'privileges';

    public function __construct(Privilege  $model)
    {
        parent::__construct();

        $this->roles = $model->roles();
        $this->privileges = $model->get();
    }

    public function index()
    {
        return $this->renderOutput('index', [
            'roles' => $this->roles,
            'privileges' => $this->privileges
        ]);
    }

    public function massUpdate(Request $request, Privilege $privileges)
    {
        $privileges->saveTable(
            $request->except([
                '_token',
                'created_at',
                'submit',
            ])
        );

        return redirect()->route('admin.privileges.index')->withStatus('msg.update');
    }
}
