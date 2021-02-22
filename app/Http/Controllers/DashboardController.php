<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Application  $app
     * @param  Request  $request
     * @param  ViewFactory  $viewFactory
     * @return Response
     */
    public function __invoke(Application $app, Request $request, ViewFactory $viewFactory)
    {
        $viewFactory->addLocation(
            $app->resourcePath(
                'dashboard/'.config('bixbite.skin').'/views'
            )
        );

        return $viewFactory->make('dashboard', [
            //
        ]);
    }
}
