<?php

namespace BBCMS\Http\Controllers\Admin;

use Gate;

use BBCMS\Models\Module;
use BBCMS\Models\User;
use BBCMS\Models\Article;
use BBCMS\Models\Category;
use BBCMS\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

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

        // return $this->renderOutput('index', [
        //     'users_count' => User::count(),
        //     'categories' => Category::with('articles')->withCount('articles')->latest()->limit(4)->get(),
        //     'categories_count' => Category::count(),
        //     'articles' => Article::with(['categories:categories.id,categories.title,categories.slug'])->latest()->limit(4)->get(),
        //     'articles_count' => Article::count(),
        // ]);

        return $this->renderOutput('index', [
            'modules' => $this->modules->where('on_mainpage', 1)->get(),
            'theme' => theme_version(app_theme()),
        ]);
    }
}
