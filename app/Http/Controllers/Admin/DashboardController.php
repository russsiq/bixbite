<?php

namespace BBCMS\Http\Controllers\Admin;

use Gate;

use BBCMS\Models\Tag;
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
        if(Gate::denies('dashboard')) {
			abort(403);
		}
        
        return $this->makeResponse('index', [
            'theme' => theme_version(app_theme()),
            'modules' => $this->modules->where('on_mainpage', 1)->get(),
        ]);
    }

    public function tagsReindex()
    {
        Tag::query()->doesntHave('articles')->get(['id'])->each->delete();
        
        return $this->makeRedirect(true, 'dashboard', __('tagsReindex!'));
    }
}
