<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        [$products, $subtotal] = $this->cartProducts();

        return view('frontend.cart.index', compact('products', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        abort_if(! $product->status, 404);

        $quantity = max(1, min(10, (int) $request->input('quantity', 1)));
        $cart = session('frontend_cart', []);
        $currentQuantity = (int) ($cart[$product->id]['quantity'] ?? 0);

        $cart[$product->id] = [
            'product_id' => $product->id,
            'quantity' => min(10, $currentQuantity + $quantity),
        ];

        session(['frontend_cart' => $cart]);

        return redirect()
            ->route('frontend.cart.index')
            ->with('message', 'Product bag me add ho gaya.');
    }

    public function update(Request $request, Product $product)
    {
        $quantity = max(1, min(10, (int) $request->input('quantity', 1)));
        $cart = session('frontend_cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $quantity;
            session(['frontend_cart' => $cart]);
        }

        return back()->with('message', 'Bag update ho gaya.');
    }

    public function remove(Product $product)
    {
        $cart = session('frontend_cart', []);
        unset($cart[$product->id]);

        session(['frontend_cart' => $cart]);

        return back()->with('message', 'Product bag se remove ho gaya.');
    }

    private function cartProducts(): array
    {
        $cart = session('frontend_cart', []);
        $products = Product::with(['category', 'shop', 'media'])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->map(function (Product $product) use ($cart) {
                $product->cart_quantity = (int) ($cart[$product->id]['quantity'] ?? 1);
                $product->cart_price = (float) ($product->discount_price ?: $product->price);
                $product->cart_total = $product->cart_price * $product->cart_quantity;

                return $product;
            });

        return [$products, $products->sum('cart_total')];
    }
}
