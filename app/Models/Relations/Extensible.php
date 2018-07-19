<?php

namespace BBCMS\Models\Relations;

use BBCMS\Models\XField;

trait Extensible
{
    public function getXFieldsAttribute()
    {
        return XField::fields($this->getTable());
    }
}
