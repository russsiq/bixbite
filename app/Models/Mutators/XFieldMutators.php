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

    public function getParamsAttribute()
    {
        if (is_null($this->attributes['params'])) {
            return 'array' == $this->attributes['type'] ? [] : null;
        }

        // parse_ini_string ???
        $params = [];
        foreach (explode(PHP_EOL, $this->attributes['params']) as $param) {
            $param = explode(',', $param);
            $params[trim($param[0])] = trim($param[1] ?? $param[0]);
        }

        return $params;
    }
}
