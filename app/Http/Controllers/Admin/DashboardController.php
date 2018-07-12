<?php

namespace BBCMS\Http\Controllers\Admin;

use Gate;

use BBCMS\Models\Module;
use BBCMS\Http\Controllers\Admin\AdminController;

class DashboardController extends AdminController
{
    protected $modules;
    protected $template = 'dashboard';

    public function __construct(Module $modules)
    {
        parent::__construct();
        
        $this->modules = $modules;
    }

    public function index()
    {
        if(Gate::denies('admin.dashboard.index')) {
			abort(403);
		}

        return $this->renderOutput('index', [
            'theme' => theme_version(app_theme()),
            'modules' => $this->modules->where('on_mainpage', 1)->get(),
        ]);
    }
}
