<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;

class ShopsController extends Controller
{
    public function index()
    {
        $shops = Shop::withCount('products')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('shop_name')
            ->get();

        $featuredProducts = Product::with(['category', 'media'])
            ->where('status', 1)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest()
            ->limit(12)
            ->get();

        return view('frontend.shops.index', compact('shops', 'featuredProducts'));
    }

    public function show(Shop $shop)
    {
        abort_if(! $shop->status, 404);

        $shop->load(['products' => function ($query) {
            $query->with(['category', 'media'])
                ->where('status', 1)
                ->orderByDesc('is_featured')
                ->latest();
        }]);

        $categories = Category::whereIn('id', $shop->products->pluck('category_id')->filter())
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('frontend.shops.show', compact('shop', 'categories'));
    }
}
