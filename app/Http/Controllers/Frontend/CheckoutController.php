<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        [$products, $subtotal] = $this->cartProducts();

        if ($products->isEmpty()) {
            return redirect()->route('frontend.cart.index')->with('message', 'Checkout ke liye pehle product add karein.');
        }

        return view('frontend.checkout.index', [
            'products' => $products,
            'subtotal' => $subtotal,
            'deliveryCharge' => 49,
            'total' => $subtotal + 49,
        ]);
    }

    public function store(Request $request)
    {
        [$products, $subtotal] = $this->cartProducts();

        if ($products->isEmpty()) {
            return redirect()->route('frontend.cart.index')->with('message', 'Bag empty hai.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_mobile' => ['required', 'string', 'max:20'],
            'delivery_address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:80'],
            'area' => ['nullable', 'string', 'max:120'],
            'pincode' => ['required', 'string', 'max:12'],
            'payment_method' => ['required', 'in:cod,online'],
            'notes' => ['nullable', 'string', 'max:500'],
            'try_cloth_selected' => ['nullable', 'boolean'],
        ]);

        $tryClothSelected = $request->boolean('try_cloth_selected');
        $returnEligible = ! $tryClothSelected;
        $zone = DeliveryZone::where('status', 1)
            ->where('pincode', $data['pincode'])
            ->orderBy('sort_order')
            ->first();
        $shopId = optional($zone)->shop_id ?: optional($products->first())->shop_id;
        $deliveryCharge = (float) (optional($zone)->delivery_charge ?? 49);
        $freeMinimum = optional($zone)->free_delivery_min_amount;

        if ($freeMinimum && $subtotal >= (float) $freeMinimum) {
            $deliveryCharge = 0;
        }

        $order = DB::transaction(function () use ($data, $products, $subtotal, $deliveryCharge, $shopId, $tryClothSelected, $returnEligible) {
            $order = Order::create([
                'shop_id' => $shopId,
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'delivery_charge' => $deliveryCharge,
                'tax_amount' => 0,
                'total_amount' => $subtotal + $deliveryCharge,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'try_cloth_selected' => $tryClothSelected,
                'return_eligible' => $returnEligible,
                'customer_name' => $data['customer_name'],
                'customer_mobile' => $data['customer_mobile'],
                'delivery_address' => $data['delivery_address'],
                'city' => $data['city'],
                'area' => $data['area'] ?? null,
                'pincode' => $data['pincode'],
                'notes' => $data['notes'] ?? null,
                'placed_at' => now(),
            ]);

            foreach ($products as $product) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'shop_id' => $product->shop_id ?: $shopId,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'price' => $product->cart_price,
                    'quantity' => $product->cart_quantity,
                    'total' => $product->cart_total,
                    'try_cloth_selected' => $tryClothSelected,
                    'return_eligible' => $returnEligible,
                ]);

                Product::where('id', $product->id)
                    ->where('stock_quantity', '>=', $product->cart_quantity)
                    ->decrement('stock_quantity', $product->cart_quantity);
            }

            $order->payments()->create([
                'payment_method' => $data['payment_method'],
                'amount' => $order->total_amount,
                'status' => 'pending',
            ]);

            return $order;
        });

        session()->forget('frontend_cart');

        return redirect()->route('frontend.orders.success', $order);
    }

    public function success(Order $order)
    {
        $order->load(['items.product.media', 'payments']);

        return view('frontend.orders.success', compact('order'));
    }

    private function cartProducts(): array
    {
        $cart = session('frontend_cart', []);
        $products = Product::with(['category', 'shop', 'media'])
            ->where('status', 1)
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
