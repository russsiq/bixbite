<?php

namespace App\Http\Controllers;

use App\Contracts\BixBiteContract;
use App\Support\BixBite;
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
    public function __invoke(
        Application $app,
        BixBiteContract $bixbite,
        Request $request,
        ViewFactory $viewFactory
    ) {
        $viewFactory->addLocation(
            $bixbite->dashboardViewsPath()
        );

        return $viewFactory->make('dashboard', [
            //
        ]);
    }
}
