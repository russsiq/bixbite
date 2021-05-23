<?php

namespace App\Models\Observers;

use App\Models\XField;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder as SchemaBuilder;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Validation\ValidationException;

class XFieldObserver
{
    /**
     * The Schema Builder instance.
     *
     * @var SchemaBuilder
     */
    protected $schemaBuilder;

    /**
     * Create a new Observer instance.
     *
     * @param  ConnectionInterface  $connection
     * @return void
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->schemaBuilder = $connection->getSchemaBuilder();
    }

    /**
     * Handle the XField "creating" event.
     *
     * @param  XField  $xField
     * @return void
     *
     * @throws ValidationException
     */
    public function creating(XField $xField): void
    {
        $this->ensureColumnDoesntAlreadyExists(
            $extensibleTable = $xField->extensible,
            $columnName = $xField->name
        );

        $this->schemaBuilder->table($extensibleTable, function (Blueprint $table) use ($xField, $columnName) {
            /** @var ColumnDefinition|null */
            $definition = null;

            switch ($xField->type) {
                case 'integer':
                case 'boolean':
                case 'text':
                case 'timestamp':
                    $definition = $table->{$xField->type}($columnName);
                    break;

                case 'string':
                case 'array':
                    $definition = $table->string($columnName, 255);
                    break;

                default:
                    throw ValidationException::withMessages([
                        'type' => trans('Unknown extra field type: [:type].', [
                            'type' => (string) $xField->type,
                        ]),
                    ]);
                    break;
            }

            if ($definition instanceof ColumnDefinition) {
                $definition->after('id')->nullable();
            }
        });
    }

    /**
     * Handle the XField "updating" event.
     *
     * @param  XField  $xField
     * @return void
     */
    public function updating(XField $xField): void
    {
        $this->ensureColumnExists(
            $extensibleTable = $xField->extensible,
            $columnName = $xField->name
        );
    }

    /**
     * Handle the XField "deleting" event.
     *
     * @param  XField  $xField
     * @return void
     */
    public function deleting(XField $xField): void
    {
        $this->ensureColumnExists(
            $extensibleTable = $xField->extensible,
            $columnName = $xField->name
        );

        $this->schemaBuilder->table(
            $extensibleTable,
            fn (Blueprint $table) => $table->dropColumn($columnName)
        );
    }

    /**
     * Determine if the given table has a given column.
     *
     * @param  string  $table
     * @param  string  $column
     * @return bool
     */
    protected function columnExist(string $table, string $column): bool
    {
        return $this->schemaBuilder->hasColumn($table, $column);
    }

    /**
     * Determine if the given column doesn't exist in the given table.
     *
     * @param  string  $table
     * @param  string  $column
     * @return bool
     */
    protected function columnMissing(string $table, string $column): bool
    {
        return ! $this->columnExist($table, $column);
    }

    /**
     * Ensure that a table with the given column doesn't already exist.
     *
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
                'name' => trans('Column [:name] in table [:table] already exists.', [
                    'name' => $column,
                    'table' => $table,
                ]),
            ]);
        }
    }

    /**
     * Ensure that a table with the given column exists.
     *
     * For example, before updating / deleting a column.
     *
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
                'name' => trans('Column [:name] in table [:table] does not exists.', [
                    'name' => $column,
                    'table' => $table,
                ]),
            ]);
        }
    }
}
