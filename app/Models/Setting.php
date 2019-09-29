<?php

namespace BBCMS\Models;

use DB;
use Artisan;
use Storage;

use BBCMS\Models\Module;
use BBCMS\Models\BaseModel;
use BBCMS\Models\Observers\SettingObserver;

use Illuminate\Support\Collection as BaseCollection;

class Setting extends BaseModel
{
    use Traits\Dataviewer;

    protected $primaryKey = 'id';

    protected $table = 'settings';

    public $timestamps = false;

    protected $fillable = [
        'module_name',
        'name',
        'type',
        'value',
    ];

    protected $allowedFilters = [
        //
    ];

    protected $orderableColumns = [
        'id',
        'module_name',
        'name',
        'created_at',
    ];

    protected static $_env = [
        'APP_ENV',
        'APP_LOCALE',
        'APP_NAME',
        'APP_THEME',
        'APP_SKIN',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(SettingObserver::class);
    }

    // Relation
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_name', 'name', 'module');
    }

    /**
     * Обновить настройки модуля, пришедшие от пользователя.
     * @param  Module $module
     * @param  array  $attributes
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function massUpdateByModule(Module $module, array $attributes)
    {
        // 1 Извлечь все настройки модуля.
        $settings = $module->settings()->get();

        // 2 Пробежаться по коллекции, изменить значения настроек.
        $settings->each(function ($setting, $key) use ($attributes) {
            $setting->value = $attributes[$setting->name];
        });

        // 4 Сохранить настройки модуля в БД.
        $module->settings()->saveMany($settings);

        // $this->fireModelEvent('massUpdateByModule', false);

        // 5 Получить массив обновляемых настроек для сохранения в файл.
        $updated = $settings->mapWithKeys(function ($setting) {
                return [
                    $setting->name => $setting->value
                ];
            });

        // 6 Обновление настроек в директории `config/settings`.
        $path = config_path('settings');
        $file = $path.DS.$module->name.'.php';
        $content = '<?php return '.var_export($updated->toArray(), true).';';

        if (! \File::isDirectory($path)) {
            \File::makeDirectory($path);
        }

        \File::put($file, $content, true);

        // 8 Очистить и закешировать настройки.
        Artisan::call('config:cache');

        // 9 Записать переменные окружения в файл.
        self::pushToEnvFile($updated);

        // 10 Возвращаем измененные настройки для модуля.
        return $settings;
    }

    /**
     * pushToEnvFile.
     *
     * @param  BaseCollection  $collection
     * @return void
     *
    * @throws \Exception
     */
    protected static function pushToEnvFile(BaseCollection $collection)
    {
        $env_file = app()->basePath('.env');

        $collection = $collection->mapWithKeys(function ($value, $key) {
                return [
                    mb_strtoupper($key, 'UTF-8') => $value
                ];
            })
            ->filter(function ($value, $key) {
                return in_array($key, self::$_env);
            });

        if ($collection->isEmpty()) {
            return null;
        }

        // В случае ошибки синтаксиса, данная функция вернет FALSE, а не пустой массив.
        if (! $env = parse_ini_file($env_file, false, INI_SCANNER_RAW)) {
            throw new \Exception(trans('common.msg.env_fails'));
        }

        $content = collect($env)
            ->merge($collection)
            ->transform(function ($item, $key) {
                // https://laravel.com/docs/5.8/upgrade#environment-variable-parsing
                return $key . '=' . (str_contains($item, [' ', '=', '$']) ? '"' . $item . '"' : $item);
            })
            ->unique()
            ->sort()
            ->implode(PHP_EOL);

        \File::put($env_file, $content.PHP_EOL, true);

        return true;
    }

    public static function getAllowedFieldTypes()
    {

        // <input type="button">
        // <input type="checkbox">
        // <input type="color">
        // <input type="date">
        // <input type="datetime">
        // <input type="datetime-local">
        // <input type="email">
        // <input type="file">
        // <input type="hidden">
        // <input type="image">
        // <input type="month">
        // <input type="number">
        // <input type="password">
        // <input type="radio">
        // <input type="range">
        // <input type="reset">
        // <input type="search">
        // <input type="submit">
        // <input type="tel">
        // <input type="text">
        // <input type="time">
        // <input type="url">
        // <input type="week">

        return [
            'string',
            'text',
            'text-inline',
            'bool',
            'float',
            'integer',
            'select',
            'select-with',
            'select-lang',
            'select-skins',
            'select-themes',
            'select-font',
            // 'select-dir',
            'hidden',
            'email',
            'password',
            'url',
            'search',
            'datetime-local',
            'file',
            'button',
            'submit',
            'html',
            'manual',
            'captcha',
        ];
    }
}
