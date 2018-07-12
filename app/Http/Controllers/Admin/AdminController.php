<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Article;
use BBCMS\Http\Controllers\BaseController;

class AdminController extends BaseController
{
    protected $template;

    public function __construct()
    {
        // Duplicating middleware
        $this->middleware(['auth', 'can:global.admin']);
    }

    protected function renderOutput(string $template, array $vars = [])
    {
        if (! view()->exists($tpl = $this->template . '.'. $template)) {
            abort(404);
        }

        $vars = array_merge($vars, [
            'unpublished' => Article::select(['id', 'user_id', 'state'])
                ->where('user_id', user('id'))
                ->where('state', '<>', 'published')
                ->get(),
        ]);

        return view($tpl, $vars)->render();
    }
}
