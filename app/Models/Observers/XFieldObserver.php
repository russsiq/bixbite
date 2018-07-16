<?php

// retrieved, creating, created, updating, updated, saving, saved, deleting,  deleted, restoring, restored
// Mass assigned not Observed.

namespace BBCMS\Models\Observers;

use BBCMS\Models\XField;
use BBCMS\Models\Traits\CacheForgetByKeys;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class XFieldObserver
{
    use CacheForgetByKeys;

    protected $keysToForgetCache = [
        'x_fields' => 'fields',
    ];

    // Not used.
    protected $methodsToForgetCache = [
        'created', 'updated', 'saved', 'restored', 'deleted'
    ];

    public function creating(XField $x_field)
    {
        if ($this->columnExists($x_field->extensible, $x_field->name)) {
            return $this->fireColumnExists($x_field->extensible, $x_field->name);
        }

        Schema::table($x_field->extensible, function (Blueprint $table) use ($x_field) {
            if ('array' == $x_field->type) {
                $table->text($x_field->name)->after('id')->nullable();
            } else {
                $table->{$x_field->type}($x_field->name)->after('id')->nullable();
            }
        });
    }

    public function updating(XField $x_field)
    {
        if (! $this->columnExists($table = $x_field->extensible, $column = $x_field->name)) {
            return $this->fireColumnNotExists($table, $column);
        }
    }

    public function deleting(XField $x_field)
    {
        if (! $this->columnExists($table = $x_field->extensible, $column = $x_field->name)) {
            return $this->fireColumnNotExists($table, $column);
        }

        Schema::table($table, function (Blueprint $table) use ($column) {
            $table->dropColumn($column);
        });
    }

    protected function columnExists(string $table, string $column): bool
    {
        return Schema::hasColumn($table, $column);
    }

    protected function fireColumnExists(string $table, string $column): bool
    {
        info(sprintf(
            'Column `%s` in table `%s` already exists.', $table, $column
        ));

        return false;
    }

    protected function fireColumnNotExists(string $table, string $column): bool
    {
        info(sprintf(
            'Column `%s` in table `%s` does not exists.', $table, $column
        ));

        return false;
    }

    public function saved(XField $x_field)
    {
        // Clear and rebuild the cache.
        $this->cacheForgetByKeys($x_field);
    }

    public function deleted(XField $x_field)
    {
        // Clear and rebuild the cache.
        $this->cacheForgetByKeys($x_field);
    }
}
