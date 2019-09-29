<?php

namespace BBCMS\Http\Controllers\Setup;

use BBCMS\Exceptions\InstallerFailed;
use BBCMS\Http\Controllers\Setup\SetupController;

use Illuminate\Support\Collection as BaseCollection;

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

        $data = collect([
                '#APP_ENV' => '[production,local,dev]',
                'APP_DEBUG' => false,
                'APP_ENV' => 'production',
                'APP_KEY' => $key,
                'APP_LOCALE' => 'ru',
                'APP_NAME' => 'BixBite',
                'APP_THEME' => 'default',
                'APP_SKIN' => 'default',
                'APP_URL' => app('url')->to('/'),

                'AWS_ACCESS_KEY_ID'=>'',
                'AWS_SECRET_ACCESS_KEY'=>'',
                'AWS_DEFAULT_REGION'=>'us-east-1',
                'AWS_BUCKET'=>'',

                'BROADCAST_DRIVER' => 'log',
                'CACHE_DRIVER' => 'file',
                'LOG_CHANNEL' => 'stack',

                'MAIL_DRIVER' => 'smtp',
                'MAIL_HOST' => 'smtp.mailtrap.io',
                'MAIL_PORT' => 2525,
                'MAIL_USERNAME' => null,
                'MAIL_PASSWORD' => null,
                'MAIL_ENCRYPTION' => null,

                'PUSHER_APP_ID' => '',
                'PUSHER_APP_KEY' => '',
                'PUSHER_APP_SECRET' => '',
                'PUSHER_APP_CLUSTER' => 'mt1',

                'CACHE_DRIVER'=>'file',
                'QUEUE_CONNECTION'=>'sync',

                'REDIS_HOST' => '127.0.0.1',
                'REDIS_PASSWORD' => null,
                'REDIS_PORT' => 6379,

                'SESSION_DRIVER' => 'file',
                'SESSION_LIFETIME' => 120,

                'MIX_APP_URL' => '"${APP_URL}"',
                'MIX_APP_THEME' => '"${APP_THEME}"',
                'MIX_APP_SKIN' => '"${APP_SKIN}"',
                'MIX_PUSHER_APP_KEY' => '"${PUSHER_APP_KEY}"',
                'MIX_PUSHER_APP_CLUSTER' => '"${PUSHER_APP_CLUSTER}"',
            ])
            ->transform(function ($item, $key) {
                // https://laravel.com/docs/5.8/upgrade#environment-variable-parsing
                return $key . '=' . (str_contains($item, [' ', '=']) ? '"' . $item . '"' : $item);
            })
            ->unique()
            ->sort()
            ->implode(PHP_EOL);

        file_put_contents(app()->basePath('.env'), $data.PHP_EOL, LOCK_EX);
    }

    /**
     * pushToEnvFile.
     *
     * @param  BaseCollection  $collection
     * @return void
     *
    * @throws \Exceptions\InstallerFailed
     */
    protected function pushToEnvFile(BaseCollection $collection)
    {
        $env_file = app()->basePath('.env');

        $collection = $collection->filter(function ($value, $key) {
            return str_contains($key, ['_']) and mb_strtoupper($key, 'UTF-8') === $key;
        });

        if ($collection->isEmpty()) {
            return null;
        }

        // В случае ошибки синтаксиса, данная функция вернет FALSE, а не пустой массив.
        if (! $env = parse_ini_file($env_file, false, INI_SCANNER_RAW)) {
            throw new InstallerException(trans('common.msg.env_fails'));
        }

        $data = collect($env)
            ->merge($collection)
            ->transform(function ($item, $key) {
                // https://laravel.com/docs/5.8/upgrade#environment-variable-parsing
                return $key . '=' . (str_contains($item, [' ', '=']) ? '"' . $item . '"' : $item);
            })
            ->unique()
            ->sort()
            ->implode(PHP_EOL);

        file_put_contents($env_file, $data.PHP_EOL, LOCK_EX);

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
