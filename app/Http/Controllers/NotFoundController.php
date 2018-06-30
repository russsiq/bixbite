<?php

namespace BBCMS\Http\Controllers;

use BBCMS\Http\Controllers\BaseController;

class NotFoundController extends BaseController
{
    public function __invoke()
    {
        return response()
            ->view('errors', [
                'title' => 'Page Not Found',
                'message' => 'Sorry, the page you are looking for could not be found.'
            ], 404);
    }
}
