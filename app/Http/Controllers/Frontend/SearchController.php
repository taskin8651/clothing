<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $products = $this->queryProducts($request)
            ->paginate(18)
            ->withQueryString();

        $wishlistIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->all()
            : [];

        return view('frontend.search.index', compact('products', 'categories', 'wishlistIds'));
    }

    private function queryProducts(Request $request)
    {
        return Product::with(['category', 'shop', 'media'])
            ->where('status', 1)
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';
                $query->where(function ($search) use ($term) {
                    $search->where('name', 'like', $term)
                        ->orWhere('brand', 'like', $term)
                        ->orWhere('sku', 'like', $term)
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', $term));
                });
            })
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->category_id))
            ->when($request->filled('min_price'), fn ($query) => $query->whereRaw('COALESCE(discount_price, price) >= ?', [(float) $request->min_price]))
            ->when($request->filled('max_price'), fn ($query) => $query->whereRaw('COALESCE(discount_price, price) <= ?', [(float) $request->max_price]))
            ->when($request->boolean('try_cloth'), fn ($query) => $query->where('try_cloth_available', 1))
            ->when($request->boolean('return_available'), fn ($query) => $query->where('return_available', 1))
            ->when($request->sort === 'price_low', fn ($query) => $query->orderByRaw('COALESCE(discount_price, price) asc'))
            ->when($request->sort === 'price_high', fn ($query) => $query->orderByRaw('COALESCE(discount_price, price) desc'))
            ->when($request->sort === 'latest', fn ($query) => $query->latest())
            ->when(! in_array($request->sort, ['price_low', 'price_high', 'latest'], true), function ($query) {
                $query->orderByDesc('is_featured')->latest();
            });
    }
}
