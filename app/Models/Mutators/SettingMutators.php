<?php

namespace BBCMS\Models\Mutators;

trait SettingMutators
{
    public function getTitleAttribute()
    {
        return $this->attributes['title'] ?? __($this->attributes['name']);
    }

    public function getDescrAttribute()
    {
        $descr = $this->attributes['name'].'#descr';
        return ($descr != __($descr)) ? __($descr) : $this->attributes['descr'];
    }
}
