<?php

namespace App\Queries;

use App\Models\Jobs\Category;

class CategoryQuery
{
    //TODO this code was added
    public static function onlyParent()
    {
        return Category::query()->whereNull('parent_id');
    }
}