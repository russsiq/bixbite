<?php

namespace BBCMS\Http\Controllers\Setup;

use BBCMS\Http\Controllers\BaseController;

class SetupController extends BaseController
{
    protected $template = 'setup';

    public function __construct()
    {

    }

    protected function renderOutput(string $template, array $vars = [])
    {
        if (view()->exists($this->template . '.'. $template)) {
            return view($this->template . '.'. $template, $vars)->render();
        }

        abort(404);
    }
}
