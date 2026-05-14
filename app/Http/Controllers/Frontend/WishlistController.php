<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with(['product.category', 'product.media'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('frontend.wishlist.index', compact('wishlists'));
    }

    public function toggle(Product $product)
    {
        abort_if(! $product->status, 404);

        $wishlist = Wishlist::withTrashed()
            ->where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist && ! $wishlist->trashed()) {
            $wishlist->delete();
            return back()->with('message', 'Wishlist se remove ho gaya.');
        }

        if ($wishlist && $wishlist->trashed()) {
            $wishlist->restore();
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
            ]);
        }

        return back()->with('message', 'Wishlist me add ho gaya.');
    }
}
