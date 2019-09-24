<?php

namespace BBCMS\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WidgetController extends Controller
{
    public function __construct()
    {

    }

    public function provide(Request $request, string $widget)
    {
        return app('widget')->make($widget, $request->all());
    }
}
