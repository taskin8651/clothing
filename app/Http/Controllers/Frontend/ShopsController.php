<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

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

    public function show(Request $request, Shop $shop)
    {
        abort_if(! $shop->status, 404);

        $shop->load(['products' => function ($query) use ($request) {
            $query->with(['category', 'media'])
                ->where('status', 1)
                ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
                ->when($request->boolean('try_cloth'), fn ($q) => $q->where('try_cloth_available', 1))
                ->when($request->boolean('return_available'), fn ($q) => $q->where('return_available', 1))
                ->when($request->sort === 'price_low', fn ($q) => $q->orderByRaw('COALESCE(discount_price, price) asc'))
                ->when($request->sort === 'price_high', fn ($q) => $q->orderByRaw('COALESCE(discount_price, price) desc'))
                ->when($request->sort === 'latest', fn ($q) => $q->latest())
                ->when(! in_array($request->sort, ['price_low', 'price_high', 'latest'], true), fn ($q) => $q->orderByDesc('is_featured')->latest());
        }]);

        $categories = Category::whereIn('id', $shop->products->pluck('category_id')->filter())
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('frontend.shops.show', compact('shop', 'categories'));
    }
}
