<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(18)
            ->get();

        return view('frontend.categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        abort_if(! $category->status, 404);

        $category->load(['products' => function ($query) {
            $query->with(['shop', 'media'])
                ->where('status', 1)
                ->orderByDesc('is_featured')
                ->latest();
        }]);

        $siblingCategories = Category::where('status', 1)
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        return view('frontend.categories.show', compact('category', 'siblingCategories'));
    }
}
