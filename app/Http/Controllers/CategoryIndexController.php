<?php

// Controller for a list of sorted categories and items

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryIndexController extends Controller
{
    public function __invoke()
    {
        // using eager loading
        $categories = Category::with(['items' => function ($query) {
            $query->orderedByMinPrice();
        }])->orderedByName()->get();

        return CategoryResource::collection($categories);
    }
}
