<?php

namespace BBCMS\Models\Observers;

use BBCMS\Models\Category;

class CategoryObserver
{

    public function created(Category $category)
    {

    }

    public function saved(Category $category)
    {

    }

    public function updated(Category $category)
    {

    }

    public function otherUpdating()
    {
        dd('otherUpdating');
        $this->cacheForget();
    }

    public function deleted(Category $category)
    {

    }
}
