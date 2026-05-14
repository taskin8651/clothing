<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller
{
    public function show(Product $product)
    {
        abort_if(! $product->status, 404);

        $product->load(['shop', 'category', 'variants', 'media']);
        $this->rememberRecentlyViewed($product);

        $relatedProducts = Product::with(['category', 'media'])
            ->where('status', 1)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($query) => $query->where('category_id', $product->category_id))
            ->latest()
            ->limit(6)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    private function rememberRecentlyViewed(Product $product): void
    {
        $ids = session('recently_viewed_products', []);
        $ids = array_values(array_unique(array_merge([$product->id], array_diff($ids, [$product->id]))));

        session(['recently_viewed_products' => array_slice($ids, 0, 10)]);
    }
}
