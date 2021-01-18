<?php

define('DS', DIRECTORY_SEPARATOR);

/**
 * app_locale - Get current lang.
 * app_theme - Get current theme name.
 * get_avatar - Get avatar url for img tag using specified user ID or email.
 * get_gravatar - Get Gravatar url for img tag using specified email.
 * pageinfo - Working with global information about the page.
 * select_dir (optional: `custom_views`, and folder in resource_path($path))
 * select_file - Get all name of the files within a given directory. Used function glob().
 * setting - Get a config with user setting from 'config\settings\*.php'.
 * skin - Generate a url to asset for current skin of application.
 * skin_path - Get path to `resources/skins/{skin}/{path}` folder.
 * theme - Generate a url to asset for current theme of application.
 * theme_path - Get path to `resources\themes\\{theme-from-setting}\\{path}` resources $directory.
 * theme_version - Obtaining info about theme from version file.
 * user - Return curent logined user $attribute. Used only in admin panel.
 * wrap_attr - Wrapping entity attributes with html-tags by template.
 */

// Сторонние зависимости.
use App\Exceptions\BadLogic;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

use voku\helper\ASCII;

if (! function_exists('app_locale')) {
    /**
     * Get current lang.
     * @return string  Ex.: en, ru, etc.
     */
    function app_locale(): string
    {
        return app()->getLocale();
    }
}

if (! function_exists('app_theme')) {
    /**
     * Get current theme name.
     * @return string  Name of folder theme. Ex.: default.
     */
    function app_theme(): string
    {
        return setting('system.app_theme', 'default');
    }
}

if (! function_exists('get_avatar')) {
    /**
     * Get avatar url for img tag using specified user ID or email.
     *
     * @param  string $email The email address.
     * @param  string $user_avatar If registered user has avatar.
     * @return mixed containing either just a URL or a complete image tag.
     */
    function get_avatar($email, $user_avatar = null)
    {
        if (! empty($user_avatar)) {
            return asset('storage/avatars/' . $user_avatar);
        } elseif (is_null($email) or ! setting('users.gravatar_used')) {
            return asset('storage/avatars/noavatar.png');
        }

        return get_gravatar($email);
    }
}

if (! function_exists('get_gravatar')) {
    /**
     * Get Gravatar url for img tag using specified email.
     *
     * @param  string    $email
     * @param  int       $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param  string    $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     *
     * @return string   containing either just a URL or a complete image tag
     */
    function get_gravatar(string $email, int $s = 74, string $d = 'mm')
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d";

        return $url;
    }
}

if (! function_exists('html_raw')) {
    /**
     * Convert a given html to HtmlString object.
     *
     * @param  string  $html
     * @return string \Illuminate\Support\HtmlString
     */
    function html_raw(string $html)
    {
        return new HtmlString($html);
    }
}

if (! function_exists('pageinfo')) {
    /**
     * Working with global information about the current frontend page.
     *
     * @param  array|string  $data
     * @throws \BadMethodCallException  If $data not a string or array.
     * @return App\Support\PageInfo|App\Support\Contracts\PageInfoContract
     */
    function pageinfo($data = null)
    {
        $factory = app('pageinfo');

        if (func_num_args() === 0) {
            return $factory;
        }

        if (is_string($data)) {
            return $factory->get($data);
        }

        if (is_array($data)) {
            return $factory->make($data);
        }

        throw new \BadMethodCallException;
    }
}

if (! function_exists('select_dir')) {
    /**
     * Get all name of the directories within a given directory.
     *
     * Special $path: `custom_views`, and folder in resource_path($path).
     *
     * @param  string    $path
     * @param  boolean   $with_default Added [null => 'By default']
     * @return array
     */
    function select_dir(string $path, bool $with_default = false)
    {
        $dirs = $with_default ? ['' => __('By default')] : [];

        if ('custom_views' == $path) {
            if (is_null($path = theme_path('views' . DS . 'custom_views'))) {
                return $dirs;
            }
        }

        if (is_dir($path) or is_dir($path = resource_path($path))) {
            $dir = new \DirectoryIterator($path);
            foreach ($dir as $fileinfo) {
                $filename = $fileinfo->getFilename();
                if ($fileinfo->isDir() and ! $fileinfo->isDot() and ! Str::startsWith($filename, '_')) {
                    $dirs[$filename] = $filename;
                }
            }
        }

        return $dirs;
    }
}

if (! function_exists('select_file')) {
    /**
     * Get all name of the files within a given directory. Used function glob().
     *
     * Special $path: `fonts`
     *
     * @param  string    $path
     * @param  boolean   $with_default Added [null => 'By default']
     * @return array
     */
    function select_file(string $path, string $pattern = '*.[tT][tT][fF]', bool $with_default = false)
    {
        $files = $with_default ? ['' => __('By default')] : [];

        $path = trim($path, '/');

        if (is_dir($path) or is_dir($path = resource_path($path))) {
            foreach (glob($path.DS.$pattern) as $file) {
                $file = pathinfo($file)['filename'];
                $files[$file] = $file;
            }
        }

        return $files;
    }
}

if (! function_exists('setting')) {
    /**
     * Get a config with user setting.
     *
     * @param  string  $environment The strig of config file settings
     * @param  string  $default
     *
     * @return Config containing setting
     */
    function setting(string $environment = null, string $default = null)
    {
        return config('settings' . ($environment ? '.' . $environment : null), $default);
    }
}

if (! function_exists('skin')) {
    /**
     * Generate a url to asset for current skin of application.
     * Ex.: `http://site.com/skins/{skin-from-setting}/css/app.css`.
     *
     * @param  string  $path
     * @param  bool    $secure
     * @return string
     */
    function skin($path, $secure = null)
    {
        return asset('skins/' . setting('system.app_skin', 'default') . '/' . $path, $secure);
    }
}

if (! function_exists('skin_path')) {
    /**
     * Get path to `resources/skins/{skin}/{path}` folder.
     *
     * @param  string $path
     * @return string
     */
    function skin_path(string $path = null)
    {
        $directory = resource_path('skins' . DS . setting('system.app_skin', 'default')) . ($path ? DS . $path : $path);

        return is_dir($directory) ? $directory : null;
    }
}

if (! function_exists('theme')) {
    /**
     * Generate a url to asset for current theme of application.
     * Ex.: `http://site.com/themes/{theme-from-setting}/css/app.css`.
     *
     * @param  string $path
     * @param  bool $secure
     * @return string
     */
    function theme($path, $secure = null)
    {
        return asset('themes/' . app_theme() . '/' . $path, $secure);
    }
}

if (! function_exists('theme_path')) {
    /**
     * Get path to `resources/themes/{theme}/{path}` directory.
     *
     * @param  string $path
     * @return string
     */
    function theme_path(string $path = null)
    {
        $dir = resource_path('themes' . DS . app_theme()) . ($path ? DS . $path : $path) . DS;

        return is_dir($dir) ? $dir : null;
    }
}

if (! function_exists('theme_version')) {
    /**
     * Obtaining info about theme from `resources/themes/{theme}/version` file.
     *
     * @param  string $theme
     * @return object|null
     */
    function theme_version(string $theme)
    {
        $locale = app_locale();
        $path = resource_path('themes' . DS . $theme . DS);
        $file = $path . 'version';

        if (! file_exists($file) or ! $version = parse_ini_file($file, true, INI_SCANNER_RAW)) {
            return null;
        }

        if (! empty($version['screenshot']) and file_exists($path.'public'.DS.$version['screenshot'])) {
            $screenshot = asset("themes/$theme/public/$version[screenshot]");
        }

        return (object) array_merge($version, [
            'name' => $theme,
            'author' => $version['author'] ?? null,
            'screenshot' => $screenshot ?? null,
            'title' => $version['title'][$locale] ?? null,
            'description' => $version['description'][$locale] ?? null,
        ]);
    }
}

if (! function_exists('skin_path')) {
    /**
     * Get path to `resources/skins/{skin}/{path}` folder.
     *
     * @param  string $path
     * @return string
     */
    function skin_path(string $path = null)
    {
        $directory = resource_path('skins' . DS . setting('system.app_skin', 'default')) . ($path ? DS . $path : $path);

        return is_dir($directory) ? $directory : null;
    }
}

if (! function_exists('user')) {
    /**
     * Return attribute for currently logined user.
     *
     * @param  string $attribute
     * @param  string $guard
     * @throws BadLogic When requesting hidden attributes.
     * @return null|string|App\Models\User
     */
    function user(string $attribute = null, string $guard = null)
    {
        if (auth('sanctum')->check()) {
            $guard = 'sanctum';
        } elseif (! auth($guard)->check()) {
            return null;
        }

        $user = auth($guard)->user();

        if (in_array($attribute, $user->getHidden())) {
            throw new BadLogic;
        }

        return $attribute ? $user->getAttribute($attribute) : $user;
    }
}

if (! function_exists('wrap_attr')) {
    /**
     * Wrapping entity attributes with html-tags by template.
     *
     * @param  object|array $entities
     * @param  string       $template
     * @param  string       $separator
     * @param  string       $wrapper
     * @return string|null
     */
    function wrap_attr($entities, string $template = '<a href="%url">%title</a>', string $separator = ', ', string $wrapper = null)
    {
        if (is_array($entities)) {
            $entities = (object) $entities;
        }

        if (! is_object($entities) or empty($entities)) {
            return null;
        }
        preg_match_all("#%([\w]+)#", $template, $attr, PREG_SET_ORDER);
        $output = [];
        foreach ($entities as $entity) {
            $data = [];
            foreach ($attr as $value) {
                if (isset($entity->{$value[1]})) {
                    $data[$value[0]] = $entity->{$value[1]};
                }
            }
            $output[] = str_replace(array_keys($data), array_values($data), $template);
        }

        $entities = implode($separator, $output);

        return html_raw(
            ($wrapper and $entities) ? str_replace('%entities', $entities, $wrapper) : $entities
        );
    }
}

// $mappedProduce = [];
//
// foreach ($produce as [$id, $name, $type]) {
//     $mappedProduce[] = compact('id', 'name', 'type');
// }

if (! function_exists('parse_ini')) {
    function parse_ini($file_path)
    {
        if (! file_exists($file_path)) {
            throw new Exception("File not exists ${file_path}");
        }

        $text = fopen($file_path, 'r');

        while ($line = fgets($text)) {
            list($key, $param) = explode('=', $line, 2);
            yield $key => $param;
        }
    }
}
