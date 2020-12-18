<?php

namespace App\Models;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Russsiq\EnvManager\Facades\EnvManager;

// Сторонние зависимости.
use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection as BaseCollection;

/**
 * Модель Настройки.
 */
class Setting extends BaseModel
{
    use Mutators\SettingMutators,
        Traits\Dataviewer,
        HasFactory;

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

    /**
     * Разрешенные переменные для сохранения в файл переменных окружения.
     * @var array
     */
    protected static $allowedVariablesForEnv = [
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

    /**
     * Получить модуль, к которому относится настройка.
     * @return BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_name', 'name', 'module');
    }

    /**
     * Обновить настройки модуля, пришедшие от пользователя.
     * @param  Module  $module
     * @param  array  $attributes
     * @return Collection
     */
    public static function massUpdateByModule(Module $module, array $attributes): Collection
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

        // 5 Получить коллекцию обновленных настроек для сохранения в файл.
        $updated = $settings->pluck('value', 'name');

        // 6 Обновление настроек в директории `config/settings`.
        self::updateSettingsFile($module->name, $updated);

        // 8 Записать переменные окружения в файл.
        self::pushToEnvFile($updated);

        // 9 Очистить и закешировать настройки.
        Artisan::call('config:cache');

        // 10 Возвращаем измененные настройки для модуля.
        return $settings;
    }

    /**
     * Записать доступные переменные окружения в файл.
     * @param  BaseCollection  $updated
     * @return void
     */
    protected static function pushToEnvFile(BaseCollection $updated): void
    {
        $updated = $updated->mapWithKeys(function ($value, $name) {
                return [
                    strtoupper($name) => $value
                ];
            })
            ->filter(function ($value, $name) {
                return in_array($name, self::$allowedVariablesForEnv);
            });

        if ($updated->isNotEmpty()) {
            EnvManager::setMany($updated->toArray())->save();
        }
    }

    /**
     * Обновить настройки в файл настроек модуля.
     * @param  string  $modulename
     * @param  BaseCollection  $updated
     * @return void
     */
    protected static function updateSettingsFile(string $modulename, BaseCollection $updated): void
    {
        $path = config_path('settings');
        $file = $path.DS.$modulename.'.php';
        $content = '<?php return '.var_export($updated->toArray(), true).';';

        File::ensureDirectoryExists($path);
        File::put($file, $content, true);
    }
}
