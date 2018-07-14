<?php

namespace BBCMS\Models\Observers;

use BBCMS\Models\XField;

use Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class XFieldObserver
{
    protected $cacheKey = 'x_fields';

    public function creating(XField $x_field)
    {
        if (Schema::hasColumn($x_field->extensible, $x_field->name)) {
            return false;
        }

        Schema::table($x_field->extensible, function (Blueprint $table) use ($x_field) {
            // switch (variable) {
            //     case 'value':
            //         // code...
            //         break;
            //
            //     default:
            //         // code...
            //         break;
            // }

            if ('array' == $x_field->type) {
                $table->text($x_field->name)->after('id')->nullable();
            } else {
                $table->{$x_field->type}($x_field->name)->after('id')->nullable();
            }
        });
    }

    public function created(XField $x_field)
    {
        $this->cacheForget();
    }

    public function saved(XField $x_field)
    {
        $this->cacheForget();
    }

    public function updated(XField $x_field)
    {
        $this->cacheForget();
    }

    public function deleting(XField $x_field)
    {
        if (! Schema::hasColumn($x_field->extensible, $x_field->name)) {
            return false;
        }

        Schema::table($x_field->extensible, function (Blueprint $table) use ($x_field) {
            $table->dropColumn($x_field->name);
        });
    }

    public function deleted(XField $x_field)
    {
        $this->cacheForget();
    }

    protected function cacheForget()
    {
        Cache::forget($this->cacheKey);
        dump('Кэш очищен - updated');
    }
}
