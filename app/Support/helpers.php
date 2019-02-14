<?php

define('DS', DIRECTORY_SEPARATOR);

/**
 * app_locale - Get or set current lang from different sides.
 * app_theme - Get or set current theme name from different sides.
 * cluster - Remove empty array values and joined with delimiter.
 * extract_images - Extract existing images paths array from given html string.
 * formatBytes - Format bytes to kb, mb, gb, tb.
 * get_captcha - Generate captcha html-block.
 * get_avatar - Get avatar url for img tag using specified user ID or email.
 * get_gravatar - Get Gravatar url for img tag using specified email.
 * html_clean - Remove html tags.
 * html_secure - Advanced htmlspecialchars. HTML & special symbols protection.
 * pageinfo - Working with global information about the page.
 * reading_time - Calculating of the time for which the text can be read.
 * select_dir (optional: `custom_views`, and folder in app()->resourcePath($path))
 * select_file - Get all name of the files within a given directory. Used function glob().
 * setting - Get a config with user setting from 'config\settings\*.php'.
 * skin_asset - Generate an asset for current skin of application.
 * skin_path - Get path to `resources/skins/{skin}/{path}` folder.
 * string_slug - Create a web friendly URL slug from a string.
 * teaser - Remove html tags and truncate string at the specified length.
 * theme_asset - Generate an asset path for current theme of application.
 * theme_path - Get path to `resources\themes\\{theme-from-setting}\\{path}` resources $directory.
 * theme_version - Obtaining info about theme from version file.
 * user - Return curent logined user $attribute. Used only in admin panel.
 * wrap_attr - Wrapping entity attributes with html-tags by template.
 */

use Illuminate\Support\HtmlString;
use BBCMS\Exceptions\BadLogic;
use BBCMS\Exceptions\MethodNotAvailable;

if (! function_exists('app_locale')) {
    /**
     * Get or set current lang from different sides, if dir lang exists.
     *
     * @param  string $locale
     * @throws \LogicException if dir lang not exists.
     * @return string  Ex.: en, ru, etc.
     */
    function app_locale(string $locale = null): string
    {
        static $app_locale = null;

        if (is_null($app_locale)) {
            // При сохранении настроек нужно обновлять `app_locale` и `app_theme`,
            // находящиеся в `session('')`. Нужно переделать в куки
            foreach ([
                request('app_locale'),
                $locale,
                session('app_locale'),
                setting('system.app_locale'),
                app()->getLocale(),
                'ru',
            ] as $app_locale) {
                if ($app_locale and is_dir(app()->resourcePath('lang'.DS.$app_locale))) {
                    session(['app_locale' => $app_locale]);
                    break;
                }
            }
        }

        if (is_null($app_locale)) {
            throw new \LogicException('Locale not defined! ');
        }

        return $app_locale;
    }
}

if (! function_exists('app_theme')) {
    /**
     * Get or set current theme name from different sides.
     *
     * @param  string $locale
     * @throws \LogicException if dir theme not exists.
     * @return string Name of folder theme. Ex.: default.
     */
    function app_theme(string $theme = null): string
    {
        static $app_theme = null;

        if (is_null($app_theme)) {
            // При сохранении настроек нужно обновлять `lang` и `theme`,
            // находящиеся в `session('')`
            foreach ([
                request('app_theme'),
                $theme,
                session('app_theme'),
                setting('system.app_theme'),
                env('APP_THEME'),
                'default',
            ] as $app_theme) {
                if ($app_theme and is_dir(app()->resourcePath('themes'.DS.$app_theme))) {
                    session(['app_theme' => $app_theme]);
                    break;
                }
            }
        }

        if (is_null($app_theme)) {
            throw new \LogicException('Theme not defined! ');
        }

        return $app_theme;
    }
}

if (! function_exists('cluster')) {
    /**
     * Remove empty array values and joined with delimiter.
     *
     * @param  array $array
     * @param  string $delimiter
     * @return string
     */
    function cluster(array $array, string $delimiter = ' — '): string
    {
        return join($delimiter, array_values(array_filter($array)));
    }
}

if (! function_exists('extract_images')) {
    /**
     * Extract images paths array from given html string.
     *
     * @param  string $html
     * @return array
     */
    function extract_images(string $html): array
    {
        $images = [];
        $doc = new \DOMDocument;
        if ($doc->loadHTML($html)) {
            // Analyze all founded <img> tags
            foreach ($doc->getElementsByTagName('img') as $tag) {
                // Add record if isset src="" attribute
                $images[] = $tag->getAttribute('src');
            }
        }

        return $images;
    }
}

if (! function_exists('formatBytes')) {
    /**
     * Format bytes to kb, mb, gb, tb.
     *
     * @param  integer $size
     * @param  integer $precision
     * @return integer
     */
    function formatBytes(int $size, int $precision = 2)
    {
        if ($size > 0) {
            $base = log($size) / log(1024);
            $suffixes = [
                __('bytes'),
                __('KB'),
                __('MB'),
                __('GB'),
                __('TB'),
            ];

            return round(pow(1024,$base - floor($base)),$precision).' '.$suffixes[floor($base)];
        }

        return $size;
    }
}

if (! function_exists('get_captcha')) {
    /**
     * Generate captcha html-block.
     *
     * @param  string $view
     * @return string \Illuminate\Support\HtmlString containing captcha block
     */
    function get_captcha(string $view = 'components.partials.captcha')
    {
        return html_raw(view($view)->with('captcha_rand', mt_rand() / mt_getrandmax())->render());
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
            return app('url')->asset('uploads/avatars/' . $user_avatar);
        } elseif (is_null($email) or ! setting('users.gravatar_used')) {
            return app('url')->asset('uploads/avatars/noavatar.png');
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

if (! function_exists('html_clean')) {
    /**
     * Remove html tags and non printable chars.
     *
     * @param  string  $text
     * @return string
     */
    function html_clean(string $text = null)
    {
        if (is_null($text)) {
            return null;
        }

        $old_text = $text;

        $text = preg_replace("/\>(\\x20|\t|\r|\n)+\</", '> <', $text);
        $text = strip_tags($text);
        $text = preg_replace('/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u', ' ', $text);
        $text = str_replace('  ',' ', $text);
        $text = trim($text);

        return $text === $old_text ? $text : html_clean($text);
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

if (! function_exists('html_secure')) {
    /**
     * Advanced htmlspecialchars. HTML & special symbols protection.
     *
     * @param  string|array  $text
     * @return string
     */
    function html_secure($text)
    {
        if (is_array($text)) {
            $text = array_map('html_secure', $text);
        } elseif (is_string($text)) {
            $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            $text = str_replace(['{', '<', '>', '"', "'"], ['&#123;', '&lt;', '&gt;', '&#34;', '&#039;'], $text); // '&' => '&amp;'
            $text = trim($text) ?: null;
        } else {
            return null;
        }

        return $text;
    }
}

if (! function_exists('minreq')) {
    /**
     * Get info about minimum requirements.
     *
     * @param  string    $requirement
     * @throws BadLogic  Unknown `requirement` argument.
     * @return string
     */
    function minreq(string $requirement)
    {
        switch ($requirement) {
            case 'php':
                return version_compare(phpversion(), '7.1.0', '>=') ? phpversion() : false; break;
            case 'pdo':
                // return (extension_loaded('PDO') and extension_loaded('pdo_mysql') and class_exists('PDO')) ? \DB::select(\DB::raw("select version()"))[0]->{'version()'} : false; break;
                return (extension_loaded('PDO') and class_exists('PDO')) ?? false; break;
            case 'ssl':
                return OPENSSL_VERSION_TEXT ?? false; break;
            case 'gd':
                return gd_info() ? gd_info()['GD Version'] : false; break;
            case 'finfo':
                return function_exists('finfo_open') ?? false; break; // PHP >= 7.2.0
            case 'mb':
                return extension_loaded('mbstring') ?? false; break;
            case 'tokenizer':
                return extension_loaded('tokenizer') ?? false; break;
            case 'ctype':
                return extension_loaded('ctype') ?? false; break;
            case 'json':
                return function_exists('json_encode') ?? false; break;
            case 'zlib':
                return (extension_loaded('zlib') and function_exists('ob_gzhandler')) ?? false; break;
            case 'xml':
                return function_exists('xml_parser_create') ?? false; break;
            case 'curlLibrary':
                return function_exists('curl_init') ?? false; break;
            case 'zipLibrary':
                return class_exists('ZipArchive') ?? false; break;
        }

        throw new BadLogic('Unknown requirement argument.');
    }
}

if (! function_exists('pageinfo')) {
    /**
     * Working with global information about the current frontend page.
     *
     * @param  array|string          $data
     * @throws MethodNotAvailable   If $data not a string or array.
     * @return BBCMS\Support\PageInfo|BBCMS\Support\Contracts\PageInfoContract
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

        throw new MethodNotAvailable();
    }
}

if (! function_exists('reading_time')) {
    /**
     * Calculating of the time for which the text can be read.
     *
     * @param  string    $text
     * @return string
     */
    function reading_time(string $text)
    {
       $word_count = str_word_count(strip_tags($text));
       $minutes = floor($word_count / 150);

       return $minutes.' minute'.($minutes > 1 ? 's' : '');
  }
}

if (! function_exists('select_dir')) {
    /**
     * Get all name of the directories within a given directory.
     *
     * Special $path: `custom_views`, and folder in app()->resourcePath($path).
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

        if (is_dir($path) or is_dir($path = app()->resourcePath($path))) {
            $dir = new \DirectoryIterator($path);
            foreach ($dir as $fileinfo) {
                $filename = $fileinfo->getFilename();
                if ($fileinfo->isDir() and ! $fileinfo->isDot() and ! starts_with($filename, '_')) {
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

        if (is_dir($path) or is_dir($path = app()->resourcePath($path))) {
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

// if (! function_exists('setting')) {
//
//     /**
//      * [setting description]
//      * @param  [type] $key     [description]
//      * @param  [type] $default [description]
//      * @return [type]          [description]
//      */
//     function setting($key, string $default = null)
//     {
//         if (is_null($key)) {
//             return new \BBCMS\Models\Setting();
//         }
//
//         if (is_array($key)) {
//             return \BBCMS\Models\Setting::set($key[0], $key[1]);
//         }
//
//         $value = \BBCMS\Models\Setting::get($key);
//
//         return is_null($value) ? value($default) : $value;
//     }
// }

if (! function_exists('skin_asset')) {
    /**
     * Generate an asset for current skin of application.
     *
     * @param  string  $path
     * @param  bool    $secure
     * @return string
     */
    function skin_asset($path, $secure = null)
    {
        return app('url')->asset('resources/skins/' . setting('system.app_skin', 'default') . '/public/' . $path, $secure);
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
        $directory = app()->resourcePath('skins' . DS . setting('system.app_skin', 'default')) . ($path ? DS . $path : $path);

        return is_dir($directory) ? $directory : null;
    }
}

if (! function_exists('string_slug')) {
    /**
     * Create a web friendly URL slug from a string.
     *
     * Although supported, transliteration is discouraged because:
     *      1) most web browsers support UTF-8 characters in URLs
     *      2) transliteration causes a loss of information
     *
     * @author Sean Murphy <sean@iamseanmurphy.com>
     * @param  string  $str
     * @param  string  $delimiter
     * @param  boolean $transliterate
     * @param  boolean $lowercase
     * @return string
     */
    function string_slug($str, string $delimiter = '-', bool $transliterate = true, bool $lowercase = true) // array $options
    {
        if (! is_string($str) or empty($str)) {
            return null;
        }
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string) $str, 'UTF-8');

        $options = [
            'delimiter' => $delimiter,
            'limit' => '255',
            'lowercase' => $lowercase,
            'replacements' => [],
            'transliterate' => $transliterate,
        ];

        $char_map = [
                // Latin
                'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
                'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
                'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
                'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
                'ß' => 'ss',
                'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
                'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
                'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
                'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
                'ÿ' => 'y',

                // Latin symbols
                '©' => '(c)',

                // Greek
                'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
                'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
                'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
                'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
                'Ϋ' => 'Y',
                'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
                'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
                'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
                'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
                'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

                // Turkish
                'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
                'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',

                // Russian
                'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
                'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
                'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
                'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
                'Я' => 'Ya',
                'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
                'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
                'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
                'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
                'я' => 'ya',

                // Ukrainian
                'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
                'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

                // Czech
                'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
                'Ž' => 'Z',
                'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
                'ž' => 'z',

                // Polish
                'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
                'Ż' => 'Z',
                'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
                'ż' => 'z',

                // Latvian
                'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
                'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
                'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
                'š' => 's', 'ū' => 'u', 'ž' => 'z',

                //Romanian
                'Ă' => 'A', 'ă' => 'a', 'Ș' => 'S', 'ș' => 's', 'Ț' => 'T', 'ț' => 't',
                //Vietnamese
                'ả' => 'a', 'Ả' => 'A','ạ' => 'a', 'Ạ' => 'A', 'ắ' => 'a', 'Ắ' => 'A', 'ằ' => 'a', 'Ằ' => 'A',
                'ẳ' => 'a', 'Ẳ' => 'A', 'ẵ' => 'a', 'Ẵ' => 'A', 'ặ' => 'a', 'Ặ' => 'A', 'ẩ' => 'a', 'Ẩ' => 'A',
                'Ấ' => 'A', 'ấ' => 'a', 'Ầ' => 'A', 'ầ' => 'a', 'Ơ' => 'O', 'ơ' => 'o', 'Đ' => 'D', 'đ' => 'd',
                'ẫ' => 'a', 'Ẫ' => 'A', 'ậ' => 'a', 'Ậ' => 'A', 'ẻ' => 'e', 'Ẻ' => 'E', 'ẽ' => 'e', 'Ẽ' => 'E',
                'ẹ' => 'e', 'Ẹ' => 'E', 'ế' => 'e', 'Ế' => 'E', 'ề' => 'e', 'Ề' => 'E',  'ể' => 'e', 'Ể' => 'E',
                'ễ' => 'e', 'Ễ' => 'E', 'ệ' => 'e', 'Ệ' => 'E', 'ỉ' => 'i', 'Ỉ' => 'I', 'ĩ' => 'i', 'Ĩ' => 'I',
                'ị' => 'i', 'Ị' => 'I', 'ỏ' => 'o', 'Ỏ' => 'O', 'ọ' => 'o', 'Ọ' => 'O', 'ố' => 'o', 'Ố' => 'O',
                'ồ' => 'o', 'Ồ' => 'O', 'ổ' => 'o', 'Ổ' => 'O', 'ỗ' => 'o', 'Ỗ' => 'O', 'ộ' => 'o', 'Ộ' => 'O',
                'ớ' => 'o', 'Ớ' => 'O', 'ờ' => 'o', 'Ờ' => 'O', 'ở' => 'o', 'Ở' => 'O', 'ỡ' => 'o', 'Ỡ' => 'O',
                'ợ' => 'o', 'Ợ' => 'O', 'ủ' => 'u', 'Ủ' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'ụ' => 'u', 'Ụ' => 'U',
                'ư' => 'u', 'Ư' => 'U', 'ứ' => 'u', 'Ứ' => 'U', 'ừ' => 'u', 'Ừ' => 'U', 'ử' => 'u', 'Ử' => 'U',
                'ữ' => 'u', 'Ữ' => 'U', 'ự' => 'u', 'Ự' => 'U', 'ỳ' => 'y', 'Ỳ' => 'Y', 'ỷ' => 'y', 'Ỷ' => 'Y',
                'ỹ' => 'y', 'Ỹ' => 'Y', 'ỵ' => 'y', 'Ỵ' => 'Y'
        ];

        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }
        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);
        $str = trim($str);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str ?: null;
    }
}

if (! function_exists('teaser')) {
    /**
     * Remove html tags and truncate string at the specified length.
     *
     * @param  string $text
     * @param  int $length
     * @param  string $finisher
     * @return string|null \Illuminate\Support\HtmlString
     */
    function teaser($text, int $length = 50, string $finisher = ' ...')
    {
        if (! is_string($text) or empty($text)) {
            return null;
        }

        $text = html_clean($text);
        if ((mb_strlen($text, 'UTF-8') <= $length) or (0 == $length)) {
            return $text;
        }
        $text = mb_substr($text, 0, $length, 'UTF-8');
        $text = rtrim($text, ' :!,.-\xC2\xA0');
        if (strpos($text, ' ')) {
            $text = mb_substr($text, 0, mb_strrpos($text, ' ', 'UTF-8'), 'UTF-8');
            $text = rtrim($text, ' :!,.-\xC2\xA0');
        }

        return trim($text) ? $text.$finisher : null;
    }
}

if (! function_exists('theme_asset')) {
    /**
     * Generate an asset path for current theme of application.
     * `Ex.: http://site.com/themes/{theme-from-setting}/public/css/app.css`
     *
     * @param  string $path
     * @param  bool $secure
     * @return string
     */
    function theme_asset($path, $secure = null)
    {
        return app('url')->asset('resources/themes/' . app_theme() . '/public/' . $path, $secure);
    }
}

if (! function_exists('theme_path')) {
    /**
     * Get path to `resources/themes/{theme}/{path}` $dir.
     *
     * @param  string $path
     * @return string
     */
    function theme_path(string $path = null)
    {
        return is_dir(
            $dir = app()->resourcePath('themes' . DS . app_theme()) . ($path ? DS . $path : $path) . DS
        ) ? $dir : null;
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
        $path = app()->resourcePath('themes' . DS . $theme . DS);
        $file = $path . 'version';

        if (! file_exists($file) or ! $version = parse_ini_file($file, true, INI_SCANNER_RAW)) {
            return null;
        }
        if (! empty($version['screenshot']) and file_exists($path.'public'.DS.$version['screenshot'])) {
            $screenshot = app('url')->asset("resources/themes/$theme/public/$version[screenshot]");
        }

        return (object) array_merge(
            $version,
            [
                'name' => $theme,
                'author' => $version['author'] ?? null,
                'screenshot' => $screenshot ?? null,
                'title' => $version['title'][$locale] ?? null,
                'description' => $version['description'][$locale] ?? null,
            ]
        );
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
        $directory = app()->resourcePath('skins' . DS . setting('system.app_skin', 'default')) . ($path ? DS . $path : $path);

        return is_dir($directory) ? $directory : null;
    }
}

if (! function_exists('user')) {
    /**
     * Return attribute for currently logined user.
     * @throws BadLogic When requesting hidden attributes.
     * @return string|null
     */
    function user(string $attribute = null)
    {
        if (in_array($attribute, ['password', 'remember_token'])) {
            throw new BadLogic();
        }

        if (auth()->check()) {
            return $attribute ? auth()->user()->getAttribute($attribute) : auth()->user();
        }

        return null;
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
            list($key, $param) = explode('=', $line);
            yield $key => $param;
        }
    }
}

// if (! function_exists('msg')) {
//     /**
//      * Generate info / error message.
//      * @param  array  $params [description]
//      * @param  integer $mode   Working mode.
//      *      0 - use SITE theme
//      *      1 - use ADMIN PANEL skin
//      * @param  integer $disp   Flag [display mode].
//      *     -1 - automatic mode
//      *      0 - add into mainblock
//      *      1 - print
//      *      2 - return as result
//      *      3 - redirect
//      * @return mixed
//      */
//     function msg(array $params, $mode = 0, $disp = -1)
//     {
//         global $twig, $template, $SUPRESS_TEMPLATE_SHOW;
//
//         // Set AUTO mode if $disp == -1
//         if ($disp == -1)
//             $mode = defined('ADMIN') ? 1 : 0;
//
//         // Choose working mode
//         $type = isset($params['type']) ? $params['type'] : 'success';
//         $title = isset($params['title']) ? $params['title'] : __($type);
//         $message = isset($params['message']) ? $params['message'] : '';
//         $referer = isset($params['referer']) ? $params['referer'] : null;
//
//         if (3 == $disp) {
//             $tVars = array(
//                 'title' => $title,
//                 'type' => $type,
//                 'message' => trim(db_squote($message), "'"),
//                 'linktext' => home_title,
//                 'link' => ! empty($referer) ? trim(db_squote($referer), "'") : home,
//             );
//             $SUPRESS_TEMPLATE_SHOW = 1;
//             $template['vars']['mainblock'] = $twig->render('redirect.tpl', $tVars);
//             return 1;
//         } else {
//             $msg = $twig->render((defined('ADMIN') ? tpl_actions : tpl_site) . 'alert.tpl', array(
//                 'id' => rand(8, 888),
//                 'type' => $type,
//                 'title' => trim(db_squote($title), "'"),
//                 'message' => trim(db_squote($message), "'"),
//                 ));
//         }
//
//         switch($disp) {
//             case 0:
//                 $template['vars']['mainblock'] = $msg . $template['vars']['mainblock'];
//                 break;
//             case 1:
//                 print $msg;
//                 break;
//             case 2:
//                 return $msg;
//                 break;
//             default:
//                 if ($mode) {
//                     print $msg;
//                 } else {
//                     $template['vars']['mainblock'] = $msg . $template['vars']['mainblock'];
//                 }
//                 break;
//         }
//     }
// }
