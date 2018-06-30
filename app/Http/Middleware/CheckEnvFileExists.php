<?php

// @@@@@@@@@@@@@NEED REoRGANIZE, but captcha not work

namespace BBCMS\Http\Middleware;

use Closure;
use Illuminate\Encryption\Encrypter;

class CheckEnvFileExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // NOT use config('app.key') from cache. But \Dotenv and ajax :(

        // \Debugbar::disable();
        // \Debugbar::enable();

        // 1 Determine physical location of file.
        $env_path = app()->basePath('.env');

        // 2 Determine `installer` route.
        $segment = $request->segment(1);

        // 3 Go to installer, if not exists file.
        if (! file_exists($env_path)) {
            // Allways generate new $key.
            $key = request('APP_KEY', $this->generateRandomKey());

            // This need to run Application with minimum parameters.
            config(['app.key' => $key]);

            // Redirect to installer, for initial file creation and cleared cache.
            if (! request('APP_KEY') or 'installer' != $segment) {
                return redirect()->route('system.install.step_choice', ['APP_KEY' => $key]);
            }

            // Or create file from installer
            return $next($request);
        }

        // 4 Ok, file exists. Check its contents.
        if (! $env = @parse_ini_file($env_path, false, INI_SCANNER_RAW)) {
            // Something clearly went wrong. File might be damaged.
            throw new \LogicException('Unable to read the environment file.');
        }

        // 5 Determine Application key.
        $app_key = $env['APP_KEY'];

        // 6 Determine the installed application.
        $app_set = ! empty($env['APP_SET']);

        // 7 Well, something went wrong again. The user is lost.
        if ($app_key and $app_set and 'installer' == $segment) {
            throw new \LogicException('File `.env` already exists! Delete it and continue.');
        }

        // 8 Application is not installed. Install it.
        if (! $app_set and 'installer' != $segment) {
            return redirect()->route('system.install.step_choice');
        }

        return $next($request);
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
