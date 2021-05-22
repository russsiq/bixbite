<?php

namespace App\Contracts\Actions\XField;

use App\Models\XField;

interface DeletesXField
{
    /**
     * Delete the given extra field.
     *
     * @param  XField  $x_field
     * @return int  Remote extra field ID.
     */
    public function delete(XField $x_field): int;
}
