<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

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

    public function show(Request $request, Category $category)
    {
        abort_if(! $category->status, 404);

        $category->load(['products' => function ($query) use ($request) {
            $query->with(['shop', 'media'])
                ->where('status', 1)
                ->when($request->boolean('try_cloth'), fn ($q) => $q->where('try_cloth_available', 1))
                ->when($request->boolean('return_available'), fn ($q) => $q->where('return_available', 1))
                ->when($request->sort === 'price_low', fn ($q) => $q->orderByRaw('COALESCE(discount_price, price) asc'))
                ->when($request->sort === 'price_high', fn ($q) => $q->orderByRaw('COALESCE(discount_price, price) desc'))
                ->when($request->sort === 'latest', fn ($q) => $q->latest())
                ->when(! in_array($request->sort, ['price_low', 'price_high', 'latest'], true), fn ($q) => $q->orderByDesc('is_featured')->latest());
        }]);

        $siblingCategories = Category::where('status', 1)
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        return view('frontend.categories.show', compact('category', 'siblingCategories'));
    }
}
