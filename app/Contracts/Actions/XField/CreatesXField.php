<?php

namespace App\Contracts\Actions\XField;

use App\Models\XField;

interface CreatesXField
{
    /**
     * Validate and create a newly extra field.
     *
     * @param  array  $input
     * @return XField
     */
    public function create(array $input): XField;
}
