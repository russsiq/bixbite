<?php

namespace BBCMS\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    }

    // /**
    //  * Get the default foreign key name for the model.
    //  *
    //  * @return string
    //  */
    // public function getForeignKey()
    // {
    //     parent::getForeignKey();
    //
    //     return env('DB_PREFIX') . snake_case(class_basename($this)) . '_' . $this->getKeyName();  // \DB::getTablePrefix()
    // }

}
