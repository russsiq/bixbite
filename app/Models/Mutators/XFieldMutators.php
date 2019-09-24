<?php

namespace BBCMS\Models\Mutators;

trait XFieldMutators
{
    public function setNameAttribute(string $value)
    {
        $this->attributes['name'] =  trim(str_start($value, $this->xPrefix()), '_');
    }

    public function getNameAttribute()
    {
        return trim(str_start($this->attributes['name'], $this->xPrefix()), '_');
    }
}
