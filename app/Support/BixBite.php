<?php

namespace App\Support;

use App\Contracts\BixBiteContract;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;

class BixBite implements BixBiteContract
{
    /** @var Application */
    protected $app;

    /** @var ConfigRepository */
    protected $config;

    public function __construct(Application $app, ConfigRepository $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    public function dashboard(): string
    {
        return $this->config->get('bixbite.dashboard', 'default');
    }

    public function theme(): string
    {
        return $this->config->get('bixbite.theme', 'default');
    }

    public function dashboardPath(string $path = ''): string
    {
        return $this->app->resourcePath(
            'dashboards/'
            .$this->dashboard()
            .$this->completePathFragment($path)
        );
    }

    public function themePath(string $path = ''): string
    {
        return $this->app->resourcePath(
            'themes/'
            .$this->theme()
            .$this->completePathFragment($path)
        );
    }

    public function dashboardViewsPath(string $path = ''): string
    {
        return $this->dashboardPath('views');
    }

    public function themeViewsPath(string $path = ''): string
    {
        return $this->themePath('views');
    }

    protected function completePathFragment(string $path): string
    {
        $path = trim($path, DIRECTORY_SEPARATOR);

        return $path ? DIRECTORY_SEPARATOR.$path : $path;
    }
}
