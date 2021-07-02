<?php

namespace App\Http\Controllers;

use App\Contracts\BixBiteContract;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
        BixBiteContract $bixbite,
        ConfigRepository $config,
        Request $request,
        ViewFactoryContract $view,
    ): View {
        $view->addLocation(
            $bixbite->dashboardViewsPath()
        );

        return $view->make('dashboard', [
            'scriptVariables' => [
                'page_language' => str_replace('_', '-', $app->getLocale()),
                'app_name' => $config->get('app.name'),
                'app_locale' => $app->getLocale(),
                // 'locale' => $bixbite->locale(),
                'app_url' => setting('system.app_url', url()->to('/')),
                'api_url' => url()->to('/api/v1'),
            //     'dashboard_base_url' => 'dashboard',
            ],
        ]);
    }

    /**
     * Get the variables for vue.js or another javascript.
     *
     * @return string
     */
    public function scriptVariables()
    {
        $data = json_encode([
            'locale' => $this->get('locale'),
            'csrf_token' => $this->get('csrf_token'),
            'app_name' => $this->get('app_name'),
            'app_dashboard' => $this->get('app_dashboard'),
            'app_theme' => $this->get('app_theme'),
            'app_url' => $this->get('app_url'),
            'api_url' => $this->get('api_url'),
            'dashboard' => $this->get('dashboard'),
        ]);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException(json_last_error_msg());
        }

        return $data;
    }
}
