<?php

namespace App\Models\Relations;

use App\Models\XField;

trait Extensible
{
    public function getXFieldsAttribute()
    {
        return XField::fields($this->getTable());
    }
}
