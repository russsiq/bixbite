<?php

namespace BBCMS\Http\Controllers\Setup;

use Illuminate\Support\Collection;

use BBCMS\Exceptions\InstallerFailed;
use BBCMS\Http\Controllers\Setup\SetupController;

class SystemInstall extends SetupController
{
    protected $template = 'install';

    protected $vars;
    protected $step;
    protected $steps = [
        1 => 'welcome', 2 => 'permission', 3 => 'database',
        4 => 'migrate', 5 => 'common', 6 => 'finish',
    ];
    protected $minreq = [
        'php', 'pdo', 'ssl', 'gd', 'finfo', 'mb', 'tokenizer', 'ctype', 'json', 'xml', 'zlib',
    ];
    protected $antiGlobals = [
        'register_globals', 'magic_quotes_gpc', 'magic_quotes_runtime', 'magic_quotes_sybase',
    ];
    protected $chmod = [
        'bootstrap/cache/', 'config/', 'config/settings/',
        'storage/app/backups/', 'storage/app/uploads/'
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
            $this->creatEnvFile($key);

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
                $request = app($class)->all();

                // Save to `.env` file prev request from form
                $this->pushToEnvFile(collect($request));
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

    protected function creatEnvFile(string $key = null)
    {
        $key = $key ?? $this->generateRandomKey();

        file_put_contents(app()->basePath('.env'),
            '#APP_ENV=[production,local,dev]' . PHP_EOL .
            'APP_DEBUG=true' . PHP_EOL .
            'APP_ENV=production' . PHP_EOL .
            'APP_KEY="'.$key . '"' . PHP_EOL .
            'APP_LOCALE='.app_locale() . PHP_EOL .
            'APP_NAME=BixBite' . PHP_EOL .
            'APP_THEME=default' . PHP_EOL .
            'APP_URL=' . app('url')->to('/') . PHP_EOL .
            'MIX_APP_URL="${APP_URL}"'. PHP_EOL .
            
            'BROADCAST_DRIVER=log' . PHP_EOL .
            'CACHE_DRIVER=file' . PHP_EOL .
            'LOG_CHANNEL=stack' . PHP_EOL .

            'MAIL_DRIVER=smtp' . PHP_EOL .
            'MAIL_HOST=smtp.mailtrap.io' . PHP_EOL .
            'MAIL_PORT=2525' . PHP_EOL .
            'MAIL_USERNAME=null' . PHP_EOL .
            'MAIL_PASSWORD=null' . PHP_EOL .
            'MAIL_ENCRYPTION=null' . PHP_EOL .

            'PUSHER_APP_ID=' . PHP_EOL .
            'PUSHER_APP_KEY=' . PHP_EOL .
            'PUSHER_APP_SECRET=' . PHP_EOL .
            'PUSHER_APP_CLUSTER=mt1' . PHP_EOL .

            'QUEUE_DRIVER=sync' . PHP_EOL .

            'REDIS_HOST=127.0.0.1' . PHP_EOL .
            'REDIS_PASSWORD=null' . PHP_EOL .
            'REDIS_PORT=6379' . PHP_EOL .

            'SESSION_DRIVER=file' . PHP_EOL .
            'SESSION_LIFETIME=120' . PHP_EOL
        );
    }

    /**
     * pushToEnvFile.
     *
     * @param  \Illuminate\Support\Collection $request
     * @return void
     *
    * @throws \Exceptions\InstallerFailed
     */
    protected function pushToEnvFile(Collection $request)
    {
        $request = $request->filter(function ($value, $key) {
            return str_contains($key, ['_']) and mb_strtoupper($key, 'UTF-8') === $key;
        });

        if ($request->isEmpty()) {
            return null;
        }

        // В случае ошибки синтаксиса, данная функция вернет FALSE, а не пустой массив.
        if (! $env = parse_ini_file(app()->basePath('.env'), false, INI_SCANNER_RAW)) {
            throw new InstallerException(str_replace(':theme', $theme, __('msg.env_fails')));
        }

        $data = collect($env)->merge($request)
            ->transform(function ($item, $key) {
                return $key . '=' . (str_contains($item, [' ', '=']) ? '"' . $item . '"' : $item);
            })->unique()->sort()->implode(PHP_EOL);

        file_put_contents(
            app()->basePath('.env'),
            $data . PHP_EOL,
            LOCK_EX
        );

        return true;
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey()
    {
        return 'base64:'.base64_encode(
            Encrypter::generateKey(config('app.cipher'))
        );
    }
}
