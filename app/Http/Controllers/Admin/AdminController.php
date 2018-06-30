<?php

namespace BBCMS\Http\Controllers\Admin;

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

        return view($tpl, $vars)->render();
    }
}
