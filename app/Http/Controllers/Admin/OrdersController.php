<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignDeliveryBoyRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\DeliveryBoy;
use App\Models\DeliveryTracking;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Shop;
use App\Models\User;
use App\Models\UserAddress;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::with(['customer', 'shop', 'deliveryBoy', 'items'])->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        [$customers, $addresses, $shops, $deliveryBoys, $products, $variantsByProduct] = $this->formData();

        return view('admin.orders.create', compact(
            'customers',
            'addresses',
            'shops',
            'deliveryBoys',
            'products',
            'variantsByProduct'
        ));
    }

    public function store(StoreOrderRequest $request)
    {
        DB::transaction(function () use ($request) {
            $customer = User::find($request->customer_id);
            $address = UserAddress::find($request->customer_address_id);
            $tryClothSelected = $request->has('try_cloth_selected');
            $returnEligible = ! $tryClothSelected;
            $subtotal = 0;
            $orderItems = [];
            $shopId = $request->shop_id ?: $this->findShopByPincode($address);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $variant = ! empty($item['product_variant_id']) ? ProductVariant::find($item['product_variant_id']) : null;
                $price = $item['price'] ?? ($variant && $variant->discount_price ? $variant->discount_price : null);
                $price = $price ?? ($variant && $variant->price ? $variant->price : null);
                $price = $price ?? ($product->discount_price ?: $product->price);
                $quantity = (int) $item['quantity'];
                $total = (float) $price * $quantity;
                $subtotal += $total;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $variant ? $variant->id : null,
                    'shop_id' => $product->shop_id,
                    'product_name' => $product->name,
                    'product_sku' => $variant && $variant->sku ? $variant->sku : $product->sku,
                    'size' => $variant ? $variant->size : null,
                    'color' => $variant ? $variant->color : null,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $total,
                    'try_cloth_selected' => $tryClothSelected ? 1 : 0,
                    'return_eligible' => $returnEligible ? 1 : 0,
                ];
            }

            $discountAmount = $request->discount_amount ?? 0;
            $deliveryCharge = $request->delivery_charge ?? 0;
            $taxAmount = $request->tax_amount ?? 0;
            $totalAmount = max(0, $subtotal - $discountAmount + $deliveryCharge + $taxAmount);

            $order = Order::create([
                'customer_id' => $customer ? $customer->id : null,
                'customer_address_id' => $address ? $address->id : null,
                'shop_id' => $shopId,
                'delivery_boy_id' => $request->delivery_boy_id,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'delivery_charge' => $deliveryCharge,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status ?: 'pending',
                'order_status' => $request->order_status ?: 'pending',
                'try_cloth_selected' => $tryClothSelected ? 1 : 0,
                'return_eligible' => $returnEligible ? 1 : 0,
                'customer_name' => $customer ? $customer->name : null,
                'customer_mobile' => $customer ? $customer->mobile : null,
                'delivery_address' => $address ? $address->address : null,
                'city' => $address ? $address->city : null,
                'area' => $address ? $address->area : null,
                'pincode' => $address ? $address->pincode : null,
                'latitude' => $address ? $address->latitude : null,
                'longitude' => $address ? $address->longitude : null,
                'notes' => $request->notes,
                'admin_note' => $request->admin_note,
                'placed_at' => now(),
            ]);

            foreach ($orderItems as $orderItem) {
                $order->items()->create($orderItem);
                $this->reduceStock($orderItem['product_id'], $orderItem['product_variant_id'], $orderItem['quantity']);
            }

            $this->syncStatusTimestamp($order, $order->order_status);
            $this->createStatusHistory($order, $order->order_status, 'Order created');

            $order->payments()->create([
                'payment_method' => $order->payment_method,
                'amount' => $order->total_amount,
                'status' => $order->payment_status,
                'paid_at' => $order->payment_status === 'paid' ? now() : null,
            ]);
        });

        return redirect()
            ->route('admin.orders.index')
            ->with('message', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load([
            'customer',
            'customerAddress',
            'shop',
            'deliveryBoy',
            'items.product',
            'items.productVariant',
            'statusHistories',
            'payments',
        ]);

        $deliveryBoys = DeliveryBoy::where('status', 1)->orderBy('name')->pluck('name', 'id')->prepend('Please Select', '');

        return view('admin.orders.show', compact('order', 'deliveryBoys'));
    }

    public function edit(Order $order)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load(['items.product', 'items.productVariant']);
        [$customers, $addresses, $shops, $deliveryBoys] = $this->formData(false);

        return view('admin.orders.edit', compact('order', 'customers', 'addresses', 'shops', 'deliveryBoys'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $oldStatus = $order->order_status;
        $oldPaymentStatus = $order->payment_status;
        $data = $request->validated();

        $order->update($data);

        if ($request->filled('order_status') && $request->order_status !== $oldStatus) {
            $this->syncStatusTimestamp($order, $request->order_status);
            $this->createStatusHistory($order, $request->order_status, 'Status updated by admin');
        }

        if ($request->filled('payment_status') && $request->payment_status !== $oldPaymentStatus) {
            $payment = $order->payments()->latest()->first();

            if ($payment) {
                $payment->update([
                    'payment_method' => $order->payment_method,
                    'amount' => $order->total_amount,
                    'status' => $order->payment_status,
                    'paid_at' => $order->payment_status === 'paid' ? now() : $payment->paid_at,
                ]);
            } else {
                $order->payments()->create([
                    'payment_method' => $order->payment_method,
                    'amount' => $order->total_amount,
                    'status' => $order->payment_status,
                    'paid_at' => $order->payment_status === 'paid' ? now() : null,
                ]);
            }
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('message', 'Order updated successfully.');
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $order->update(['order_status' => $request->order_status]);
        $this->syncStatusTimestamp($order, $request->order_status);
        $this->createStatusHistory($order, $request->order_status, $request->note);

        return back()->with('message', 'Order status updated successfully.');
    }

    public function assignDeliveryBoy(AssignDeliveryBoyRequest $request, Order $order)
    {
        $status = in_array($order->order_status, ['pending', 'confirmed', 'packed'], true)
            ? 'assigned'
            : $order->order_status;

        $order->update([
            'delivery_boy_id' => $request->delivery_boy_id,
            'order_status' => $status,
            'assigned_at' => now(),
        ]);

        DeliveryTracking::updateOrCreate(
            ['order_id' => $order->id],
            [
                'shop_id' => $order->shop_id,
                'delivery_boy_id' => $request->delivery_boy_id,
                'customer_id' => $order->customer_id,
                'customer_address_id' => $order->customer_address_id,
                'pickup_address' => optional($order->shop)->address,
                'delivery_address' => $order->delivery_address,
                'city' => $order->city,
                'area' => $order->area,
                'pincode' => $order->pincode,
                'status' => 'assigned',
                'cod_amount' => $order->payment_method === 'cod' ? $order->total_amount : 0,
                'assigned_at' => now(),
            ]
        );

        $this->createStatusHistory($order, $status, $request->note ?: 'Delivery boy assigned');

        return back()->with('message', 'Delivery boy assigned successfully.');
    }

    public function destroy(Order $order)
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->delete();

        return back()->with('message', 'Order deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Order::whereIn('id', request('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function formData(bool $withProducts = true): array
    {
        $customers = User::whereHas('roles', function ($query) {
                $query->where('title', 'Customer');
            })
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend('Please Select', '');

        $addresses = UserAddress::with('user')->latest()->get();
        $shops = Shop::where('status', 1)->orderBy('shop_name')->pluck('shop_name', 'id')->prepend('Please Select', '');
        $deliveryBoys = DeliveryBoy::where('status', 1)->orderBy('name')->pluck('name', 'id')->prepend('Please Select', '');

        if (! $withProducts) {
            return [$customers, $addresses, $shops, $deliveryBoys];
        }

        $products = Product::with('variants')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $variantsByProduct = $products->mapWithKeys(function ($product) {
            return [
                $product->id => $product->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'label' => trim(($variant->size ?: '-') . ' / ' . ($variant->color ?: '-') . ' / ' . ($variant->sku ?: '-')),
                        'price' => $variant->discount_price ?: $variant->price,
                    ];
                })->values(),
            ];
        });

        return [$customers, $addresses, $shops, $deliveryBoys, $products, $variantsByProduct];
    }

    private function findShopByPincode(?UserAddress $address): ?int
    {
        if (! $address || ! $address->pincode) {
            return null;
        }

        $zoneShopId = DeliveryZone::where('status', 1)
            ->where('pincode', $address->pincode)
            ->whereNotNull('shop_id')
            ->orderBy('sort_order')
            ->value('shop_id');

        if ($zoneShopId) {
            return $zoneShopId;
        }

        return Shop::where('status', 1)->where('pincode', $address->pincode)->value('id');
    }

    private function reduceStock(int $productId, ?int $variantId, int $quantity): void
    {
        if ($variantId) {
            ProductVariant::where('id', $variantId)->decrement('stock_quantity', $quantity);
            return;
        }

        Product::where('id', $productId)->decrement('stock_quantity', $quantity);
    }

    private function syncStatusTimestamp(Order $order, string $status): void
    {
        $columns = [
            'confirmed' => 'confirmed_at',
            'packed' => 'packed_at',
            'assigned' => 'assigned_at',
            'picked_up' => 'picked_up_at',
            'out_for_delivery' => 'out_for_delivery_at',
            'delivered' => 'delivered_at',
            'cancelled' => 'cancelled_at',
            'returned' => 'returned_at',
        ];

        if (isset($columns[$status]) && ! $order->{$columns[$status]}) {
            $order->update([$columns[$status] => now()]);
        }
    }

    private function createStatusHistory(Order $order, string $status, ?string $note = null): void
    {
        $order->statusHistories()->create([
            'status' => $status,
            'note' => $note,
            'changed_by_id' => auth()->id(),
            'changed_by_type' => auth()->user() ? get_class(auth()->user()) : null,
        ]);
    }
}
