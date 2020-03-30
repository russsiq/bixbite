<?php

namespace App\Models\Observers;

// Исключения.
use Illuminate\Validation\ValidationException;

// Сторонние зависимости.
use App\Models\XField;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Наблюдатель модели `XField`.
 */
class XFieldObserver extends BaseObserver
{
    /**
     * Массив ключей для очистки кэша.
     * @var array
     */
    protected $keysToForgetCache = [
        'x_fields' => 'fields',

    ];

    /**
     * Обработать событие `creating` модели.
     * @param  XField  $xField
     * @return void
     */
    public function creating(XField $xField): void
    {
        $this->ensureColumnDoesntAlreadyExists(
            $table = $xField->extensible,
            $column = $xField->name
        );

        Schema::table($table, function (Blueprint $table) use ($xField, $column) {
            if ('array' === $xField->type) {
                $table->string($column)->after('id')->nullable();
            } else {
                $table->{$xField->type}($column)->after('id')->nullable();
            }
        });
    }

    /**
     * Обработать событие `created` модели.
     * @param  XField  $xField
     * @return void
     */
    public function created(XField $xField): void
    {

    }

    /**
     * Обработать событие `updating` модели.
     * @param  XField  $xField
     * @return void
     */
    public function updating(XField $xField): void
    {
        $this->ensureColumnExists(
            $table = $xField->extensible,
            $column = $xField->name
        );
    }

    /**
     * Обработать событие `updated` модели.
     * @param  XField  $xField
     * @return void
     */
    public function updated(XField $xField): void
    {

    }

    /**
     * Обработать событие `saving` модели.
     * @param  XField  $xField
     * @return void
     */
    public function saving(XField $xField): void
    {

    }

    /**
     * Обработать событие `deleting` модели.
     * @param  XField  $xField
     * @return void
     */
    public function saved(XField $xField): void
    {
        $this->forgetCacheByKeys($xField);
    }

    /**
     * Обработать событие `deleting` модели.
     * @param  XField  $xField
     * @return void
     */
    public function deleting(XField $xField): void
    {
        $this->ensureColumnExists(
            $table = $xField->extensible,
            $column = $xField->name
        );

        Schema::table($table, function (Blueprint $table) use ($column) {
            $table->dropColumn($column);
        });
    }

    /**
     * Обработать событие `deleted` модели.
     * @param  XField  $xField
     * @return void
     */
    public function deleted(XField $xField): void
    {
        $this->forgetCacheByKeys($xField);
    }

    /**
     * Проверить существование столбца в таблице.
     * @param  string  $table
     * @param  string  $column
     * @return bool
     */
    protected function columnExist(string $table, string $column): bool
    {
        return Schema::hasColumn($table, $column);
    }

    /**
     * Проверить отсутствие столбца в таблице.
     * @param  string  $table
     * @param  string  $column
     * @return bool
     */
    protected function columnMissing(string $table, string $column): bool
    {
        return ! $this->columnExist($table, $column);
    }

    /**
     * Убедиться, что столбца не существует в таблице,
     * например, перед созданием нового столбца.
     * @param  string  $table
     * @param  string  $column
     * @return void
     *
     * @throws ValidationException
     */
    protected function ensureColumnDoesntAlreadyExists(string $table, string $column): void
    {
        if ($this->columnExist($table, $column)) {
            throw ValidationException::withMessages([
                'name' => sprintf(
                    'Column [%s] in table [%s] already exists.', $column, $table
                )
            ]);
        }
    }

    /**
     * Убедиться, что столбец существует в таблице,
     * например, перед обновлением / удалением столбца.
     * @param  string  $table
     * @param  string  $column
     * @return void
     *
     * @throws ValidationException
     */
    protected function ensureColumnExists(string $table, string $column): void
    {
        if ($this->columnMissing($table, $column)) {
            throw ValidationException::withMessages([
                'name' => sprintf(
                    'Column [%s] in table [%s] does not exists.', $column, $table
                )
            ]);
        }
    }
}
