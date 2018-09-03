<?php

namespace App\Http\Controllers\API;

use App\Models\Jobs\Category;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function show(Category $category)
    {
        $response['data'] = $category;

        $response['data']['parent'] = $category->parent;

        return response()->json($response);
    }
}