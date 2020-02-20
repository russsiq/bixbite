<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Http\Controllers\BaseController;

class PanelController extends BaseController
{
    protected $template = 'panel';

    public function __construct()
    {
        //
    }

    /**
     * Single page application catch-all route.
     * @return \Illuminate\View\View
     */
    public function __invoke()
    {
        return view($this->template.'.app');
    }
}
