<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Russsiq\EnvManager\Facades\EnvManager;

/**
 * Setting model.
 *
 * @property int $id
 * @property string $module_name
 * @property string $name
 * @property string $type
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Module $module Get the module that the setting belongs to.
 *
 * @method static \Database\Factories\SettingFactory factory()
 *
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Setting extends Model
{
    use HasFactory;
    use Mutators\SettingMutators;
    use Traits\Dataviewer;

    /**
     * The table associated with the model.
     *
     * @const string
     */
    public const TABLE = 'settings';

    /**
     * {@inheritDoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritDoc}
     */
    protected $attributes = [
        'module_name' => '',
        'name' => '',
        'type' => 'string',
        'value' => '',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'module_name' => 'string',
        'name' => 'string',
        'type' => 'string',
        'value' => 'string',
    ];

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'module_name',
        'name',
        'type',
        'value',
    ];

    /**
     * Attributes by which filtering is allowed.
     *
     * @var array
     */
    protected $allowedFilters = [
        //
    ];

    /**
     * The attributes by which sorting is allowed.
     *
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
     *
     * @var string[]
     */
    protected static $allowedVariablesForEnv = [
        'APP_DASHBOARD',
        'APP_ENV',
        'APP_LOCALE',
        'APP_NAME',
        'APP_THEME',

        'ORG_ADDRESS_LOCALITY',
        'ORG_ADDRESS_STREET',
        'ORG_CONTACT_EMAIL',
        'ORG_CONTACT_TELEPHONE',
        'ORG_NAME',
    ];

    /**
     * Cached in-memory settings collection.
     *
     * @var EloquentCollection|null
     */
    protected static $cachedSettings = null;

    /**
     * Get the collection of settings for a given module name.
     *
     * @param  string|null  $moduleName
     * @return EloquentCollection
     */
    public static function settings(string $moduleName = null): EloquentCollection
    {
        if (is_null(static::$cachedSettings)) {
            static::$cachedSettings = static::all([
                'module_name',
                'name',
                'type',
                'value',
            ]);
        }

        if (is_null($moduleName)) {
            return static::$cachedSettings;
        }

        return static::$cachedSettings->where('module_name', $moduleName);
    }

    /**
     * Get the module that the setting belongs to.
     *
     * @return BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(
            Module::class,  // $related
            'module_name',  // $foreignKey
            'name',         // $ownerKey
            'module',       // $relation
        );
    }

    /**
     * Обновить настройки модуля, пришедшие от пользователя.
     *
     * @param  Module  $module
     * @param  array  $attributes
     * @return EloquentCollection
     */
    public static function massUpdateByModule(Module $module, array $attributes): EloquentCollection
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
     *
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
     *
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

    /**
     * Получить список встроенных примитивных типизаторов.
     *
     * @return array
     */
    public function primitiveCastTypes(): array
    {
        return static::$primitiveCastTypes;
    }

    /**
     * Определить, что переданный тип относится к примитивной типизации.
     *
     * @param  string  $castType
     * @return boolean
     */
    public function isPrimitiveCastTypes(string $castType): bool
    {
        return in_array($castType, $this->primitiveCastTypes());
    }
}
