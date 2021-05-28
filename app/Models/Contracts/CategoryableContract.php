<?php

namespace App\Models\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface CategoryableContract
{
    public function categories(): MorphToMany;

    public function getCategoryAttribute(): Category;
}
