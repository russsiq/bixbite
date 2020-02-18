<?php

namespace BBCMS\Models;

use Artisan;
use File;
use Storage;

use BBCMS\Models\Module;
use BBCMS\Models\BaseModel;
use BBCMS\Models\Observers\SettingObserver;

use Illuminate\Support\Collection as BaseCollection;

use Russsiq\EnvManager\Support\Facades\EnvManager;

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
        
        'ORG_ADDRESS_LOCALITY',
        'ORG_ADDRESS_STREET',
        'ORG_CONTACT_EMAIL',
        'ORG_CONTACT_TELEPHONE',
        'ORG_NAME',

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

        if (! File::isDirectory($path)) {
            File::makeDirectory($path);
        }

        File::put($file, $content, true);

        // 8 Записать переменные окружения в файл.
        self::pushToEnvFile($updated);

        // 9 Очистить и закешировать настройки.
        Artisan::call('config:cache');

        // 10 Возвращаем измененные настройки для модуля.
        return $settings;
    }

    /**
     * Записать доступные переменные окружения в файл.
     *
     * @param  BaseCollection  $collection
     * @return void
     */
    protected static function pushToEnvFile(BaseCollection $collection)
    {
        $collection = $collection->mapWithKeys(function ($value, $key) {
                return [
                    mb_strtoupper($key, 'UTF-8') => $value
                ];
            })
            ->filter(function ($value, $key) {
                return in_array($key, self::$_env);
            });

        if ($collection->isNotEmpty()) {
            EnvManager::setMany($collection->toArray())->save();
        }
    }
}
