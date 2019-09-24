<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Http\Controllers\BaseController;

use View, Lang;

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
        // Load skin view.
        View::addLocation(skin_path('views'));

        // Load skin common lang.
        Lang::addJsonPath(skin_path('lang'));

        // Load skin section lang.
        if (is_string($section = request()->segment(2))) {
            Lang::addJsonPath(skin_path('lang' . DS . $section));
        }

        return view($this->template.'.app');
    }
}
