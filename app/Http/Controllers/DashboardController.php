<?php

namespace App\Http\Controllers;

use App\Contracts\BixBiteContract;
use Illuminate\Contracts\Foundation\Application;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Application  $app
     * @return mixed
     */
    public function __invoke(
        Application $app,
        BixBiteContract $bixbite
    ) {
        $app->view->addLocation(
            $bixbite->dashboardViewsPath()
        );

        return $app->view->make('dashboard', [
            'scriptVariables' => [
                'api_url' => $app->url->to('api/v1'),
                'app_name' => $app->config->get('app.name'),
                'app_url' => $app->url->route('home'),
                'dashboard_base_url' => 'dashboard',
            ],
        ]);
    }
}
