<?php

namespace App\Contracts\Actions\XField;

use App\Models\XField;

interface UpdatesXField
{
    /**
     * Validate and update the given extra field.
     *
     * @param  XField  $x_field
     * @param  array  $input
     * @return XField
     */
    public function update(XField $x_field, array $input): XField;
}
