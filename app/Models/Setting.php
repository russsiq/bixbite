<?php

namespace App\Models;

// Зарегистрированные фасады приложения.
use Artisan;
use File;
use EnvManager;
use Storage;

// Сторонние зависимости.
use App\Models\Module;
use Illuminate\Support\Collection as BaseCollection;

class Setting extends BaseModel
{
    use Traits\Dataviewer;

    /**
     * Таблица БД, ассоциированная с моделью.
     * @var string
     */
    protected $table = 'settings';

    /**
     * Первичный ключ таблицы БД.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Указывает, следует ли обрабатывать временные метки модели.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Атрибуты, которым разрешено массовое присвоение.
     * @var array
     */
    protected $fillable = [
        'module_name',
        'name',
        'type',
        'value',

    ];

    /**
     * Атрибуты, по которым разрешена фильтрация сущностей.
     * @var array
     */
    protected $allowedFilters = [

    ];

    /**
     * Атрибуты, по которым разрешена сортировка сущностей.
     * @var array
     */
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
