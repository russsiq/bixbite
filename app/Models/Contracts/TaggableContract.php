<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface TaggableContract
{
    public function tags(): MorphToMany;
}
