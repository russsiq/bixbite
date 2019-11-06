<?php

namespace BBCMS\Http\Controllers\Setup;

use BBCMS\Exceptions\InstallerFailed;
use BBCMS\Http\Controllers\Setup\SetupController;

use Illuminate\Support\Collection as BaseCollection;

use Russsiq\EnvManager\Support\Facades\EnvManager;

class SystemInstall extends SetupController
{
    protected $template = 'install';

    protected $vars;

    protected $step;

    protected $steps = [
        1 => 'welcome',
        2 => 'permission',
        3 => 'database',
        4 => 'migrate',
        5 => 'common',
        6 => 'finish',
    ];

    protected $minreq = [
        'php',
        'pdo',
        'ssl',
        'gd',
        'finfo',
        'mb',
        'tokenizer',
        'ctype',
        'json',
        'xml',
        'zlib',
    ];

    protected $antiGlobals = [
        'register_globals',
        'magic_quotes_gpc',
        'magic_quotes_runtime',
        'magic_quotes_sybase',
    ];

    protected $chmod = [
        'bootstrap/cache/',
        'config/',
        'config/settings/',
        'storage/app/backups/',
        'storage/app/uploads/'
    ];

    protected $requestNamespace = '\BBCMS\Http\Requests\Setup\SystemInstall';

    public function __construct()
    {
        parent::__construct();

        $this->step = (int) array_search(request('action', 'welcome'), $this->steps);

        $this->vars = [
            'steps' => $this->steps,
            'curstep' => $this->step,
            'action' => $this->steps[$this->step + 1] ?? '',
        ];
    }

    public function stepСhoice()
    {
        if ($key = request('APP_KEY')) {
            // Создаем новый файл из образца,
            // попутно генерируя ключ для приложения.
            EnvManager::newFromPath(base_path('.env.example'), true)
                // Устанавливаем необходимые значения.
                ->setMany([
                    'APP_URL' => url('/'),
                ])
                // Сохраняем новый файл в корне как `.env`.
                ->save();

            $exit_code = \Artisan::call('cache:clear');
            $exit_code = \Artisan::call('config:clear');
            $exit_code = \Artisan::call('route:clear');
            $exit_code = \Artisan::call('view:clear');

            return redirect()->route('system.install.step_choice');
        }

        if ($this->step > 1 and method_exists($this, $method = $this->steps[$this->step])) {

            // If empty errors from previous step
            if (empty($request = request()->old())) {
                $name = studly_case($this->steps[$this->step - 1] . 'Request');
                $class = $this->requestNamespace . '\\' . $name;
                $request = app($class);

                // Save to `.env` file prev request from form
                EnvManager::setMany($request->all())->save();
            }

            // Return form to current step
            return $this->makeResponse($method,
                array_merge($this->vars, $this->{$method}())
            );
        }

        return $this->makeResponse('welcome', $this->vars);
    }

    protected function permission()
    {
        $minreq = collect($this->minreq)
            ->mapWithKeys(function ($item) {
                return [
                    $item => minreq($item),
                ];
            })->all();

        $globals = collect($this->antiGlobals)
            ->mapWithKeys(function ($item) {
                return [
                    $item => ini_get($item),
                ];
            })->all();

        $chmod = collect($this->chmod)
            ->mapWithKeys(function ($item) {
                clearstatcache(true, $path = app()->basePath($item));
                return [
                    $item => (object) [
                        'perm' => ((file_exists($path) and $x = fileperms($path)) === false) ? null : (decoct($x) % 1000),
                        'status' => is_writable($path) ?? null,
                    ]
                ];
            })->all();

        return compact('minreq', 'globals', 'chmod');
    }

    protected function database()
    {
        return [];
    }

    protected function migrate()
    {
        return [];
    }

    protected function common()
    {
        return [
            'APP_URL' => app('url')->to('/'),
            'email' => 'admin@'.request()->getHttpHost(),
            'themes' => collect(select_dir('themes'))->map('theme_version')->filter(),
        ];
    }

    protected function finish()
    {
        cache()->flush();

        return [];
    }
}
