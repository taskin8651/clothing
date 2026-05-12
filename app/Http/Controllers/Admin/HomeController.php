<?php

namespace App\Http\Controllers\Admin;

use App\Models\DeliveryTracking;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Receipt;
use App\Models\ReturnRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class HomeController
{
    public function index()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $lowStockLimit = 5;

        $stats = array_merge(
            $this->orderStats($today),
            $this->paymentStats($today, $monthStart),
            $this->productStats($lowStockLimit),
            $this->customerStats(),
            $this->deliveryStats(),
            $this->returnStats(),
            $this->financeStats()
        );

        return view('home', array_merge($stats, [
            'recent_orders'             => $this->recentOrders(),
            'recent_payments'           => $this->recentPayments(),
            'recent_return_requests'    => $this->recentReturnRequests(),
            'recent_delivery_trackings' => $this->recentDeliveryTrackings(),
            'low_stock_items'           => $this->lowStockItems($lowStockLimit),
        ]));
    }

    private function orderStats(Carbon $today): array
    {
        $orders = $this->query(Order::class, 'orders');

        return [
            'total_orders'     => $orders ? (clone $orders)->count() : 0,
            'today_orders'     => $orders ? (clone $orders)->whereDate('created_at', $today)->count() : 0,
            'pending_orders'   => $orders ? (clone $orders)->where('order_status', 'pending')->count() : 0,
            'confirmed_orders' => $orders ? (clone $orders)->where('order_status', 'confirmed')->count() : 0,
            'delivered_orders' => $orders ? (clone $orders)->where('order_status', 'delivered')->count() : 0,
            'cancelled_orders' => $orders ? (clone $orders)->where('order_status', 'cancelled')->count() : 0,
            'returned_orders'  => $orders ? (clone $orders)->where('order_status', 'returned')->count() : 0,
        ];
    }

    private function paymentStats(Carbon $today, Carbon $monthStart): array
    {
        $payments = $this->query(Payment::class, 'payments');

        return [
            'total_revenue'         => $payments ? (clone $payments)->where('status', 'paid')->sum('amount') : 0,
            'today_revenue'         => $payments ? (clone $payments)->where('status', 'paid')->whereDate('paid_at', $today)->sum('amount') : 0,
            'current_month_revenue' => $payments ? (clone $payments)->where('status', 'paid')->where('paid_at', '>=', $monthStart)->sum('amount') : 0,
            'total_payments'        => $payments ? (clone $payments)->count() : 0,
            'paid_payments'         => $payments ? (clone $payments)->where('status', 'paid')->count() : 0,
            'pending_payments'      => $payments ? (clone $payments)->where('status', 'pending')->count() : 0,
            'failed_payments'       => $payments ? (clone $payments)->where('status', 'failed')->count() : 0,
            'refunded_payments'     => $payments ? (clone $payments)->where('status', 'refunded')->count() : 0,
            'cod_payments'          => $payments ? (clone $payments)->where('payment_method', 'cod')->count() : 0,
            'online_payments'       => $payments ? (clone $payments)->where('payment_method', 'online')->count() : 0,
            'cod_pending_amount'    => $payments ? (clone $payments)->where('payment_method', 'cod')->where('status', 'pending')->sum('amount') : 0,
            'online_paid_amount'    => $payments ? (clone $payments)->where('payment_method', 'online')->where('status', 'paid')->sum('amount') : 0,
            'refunded_amount'       => $payments ? (clone $payments)->where('status', 'refunded')->sum('amount') : 0,
        ];
    }

    private function productStats(int $lowStockLimit): array
    {
        $products = $this->query(Product::class, 'products');
        $variants = $this->query(ProductVariant::class, 'product_variants');

        return [
            'total_products'        => $products ? (clone $products)->count() : 0,
            'active_products'       => $products ? (clone $products)->where('status', true)->count() : 0,
            'total_variants'        => $variants ? (clone $variants)->count() : 0,
            'low_stock_products'    => $products ? (clone $products)->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', $lowStockLimit)->count() : 0,
            'out_of_stock_products' => $products ? (clone $products)->where('stock_quantity', '<=', 0)->count() : 0,
        ];
    }

    private function customerStats(): array
    {
        $customers = $this->customerQuery();

        return [
            'total_customers'          => $customers ? (clone $customers)->count() : 0,
            'active_customers'         => $customers ? (clone $customers)->where('status', true)->count() : 0,
            'customers_with_addresses' => $customers && Schema::hasTable('user_addresses')
                ? (clone $customers)->whereHas('addresses')->count()
                : 0,
        ];
    }

    private function deliveryStats(): array
    {
        $trackings = $this->query(DeliveryTracking::class, 'delivery_trackings');

        return [
            'total_delivery_trackings' => $trackings ? (clone $trackings)->count() : 0,
            'assigned_deliveries'      => $trackings ? (clone $trackings)->where('status', 'assigned')->count() : 0,
            'out_for_delivery_count'   => $trackings ? (clone $trackings)->where('status', 'out_for_delivery')->count() : 0,
            'delivered_deliveries'     => $trackings ? (clone $trackings)->where('status', 'delivered')->count() : 0,
            'failed_deliveries'        => $trackings ? (clone $trackings)->where('status', 'failed_delivery')->count() : 0,
            'cod_pending_deliveries'   => $trackings ? (clone $trackings)->where('cod_amount', '>', 0)->where('cod_collected', false)->count() : 0,
        ];
    }

    private function returnStats(): array
    {
        $returns = $this->query(ReturnRequest::class, 'return_requests');

        return [
            'total_return_requests' => $returns ? (clone $returns)->count() : 0,
            'requested_returns'     => $returns ? (clone $returns)->where('status', 'requested')->count() : 0,
            'approved_returns'      => $returns ? (clone $returns)->where('status', 'approved')->count() : 0,
            'refunded_returns'      => $returns ? (clone $returns)->where('status', 'refunded')->count() : 0,
            'rejected_returns'      => $returns ? (clone $returns)->where('status', 'rejected')->count() : 0,
        ];
    }

    private function financeStats(): array
    {
        $invoices = $this->query(Invoice::class, 'invoices');
        $receipts = $this->query(Receipt::class, 'receipts');

        return [
            'total_invoices'        => $invoices ? (clone $invoices)->count() : 0,
            'paid_invoices'         => $invoices ? (clone $invoices)->where('invoice_status', 'paid')->count() : 0,
            'due_invoices'          => $invoices ? (clone $invoices)->where('due_amount', '>', 0)->count() : 0,
            'total_due_amount'      => $invoices ? (clone $invoices)->sum('due_amount') : 0,
            'total_receipts'        => $receipts ? (clone $receipts)->count() : 0,
            'total_received_amount' => $receipts ? (clone $receipts)->where('status', 'paid')->sum('amount') : 0,
        ];
    }

    private function recentOrders()
    {
        $orders = $this->query(Order::class, 'orders');

        return $orders
            ? $orders->with(['customer', 'shop', 'deliveryBoy'])->latest()->limit(8)->get()
            : collect();
    }

    private function recentPayments()
    {
        $payments = $this->query(Payment::class, 'payments');

        return $payments
            ? $payments->with(['order.customer', 'order.shop'])->latest()->limit(8)->get()
            : collect();
    }

    private function recentReturnRequests()
    {
        $returns = $this->query(ReturnRequest::class, 'return_requests');

        return $returns
            ? $returns->with(['order', 'orderItem', 'customer', 'shop'])->latest()->limit(8)->get()
            : collect();
    }

    private function recentDeliveryTrackings()
    {
        $trackings = $this->query(DeliveryTracking::class, 'delivery_trackings');

        return $trackings
            ? $trackings->with(['order', 'deliveryBoy', 'customer', 'shop'])->latest()->limit(8)->get()
            : collect();
    }

    private function lowStockItems(int $lowStockLimit)
    {
        $items = collect();

        if ($this->hasModelTable(ProductVariant::class, 'product_variants')) {
            $items = $items->merge(ProductVariant::with('product')
                ->where('stock_quantity', '<=', $lowStockLimit)
                ->latest()
                ->limit(8)
                ->get()
                ->map(function (ProductVariant $variant) {
                    return [
                        'type'    => 'variant',
                        'product' => optional($variant->product)->name ?? 'Product',
                        'variant' => trim(($variant->size ? $variant->size : '') . ' ' . ($variant->color ? $variant->color : '')) ?: $variant->sku,
                        'stock'   => $variant->stock_quantity,
                        'url'     => route('admin.product-variants.show', $variant),
                    ];
                }));
        }

        if ($this->hasModelTable(Product::class, 'products') && $items->count() < 8) {
            $items = $items->merge(Product::query()
                ->where('stock_quantity', '<=', $lowStockLimit)
                ->latest()
                ->limit(8 - $items->count())
                ->get()
                ->map(function (Product $product) {
                    return [
                        'type'    => 'product',
                        'product' => $product->name,
                        'variant' => $product->sku ?: 'Base product',
                        'stock'   => $product->stock_quantity,
                        'url'     => route('admin.products.show', $product),
                    ];
                }));
        }

        return $items->take(8);
    }

    private function customerQuery()
    {
        if (! $this->hasModelTable(User::class, 'users') || ! Schema::hasTable('roles') || ! Schema::hasTable('role_user')) {
            return null;
        }

        return User::whereHas('roles', function ($query) {
            $query->where('title', 'Customer');
        });
    }

    private function query(string $class, string $table)
    {
        return $this->hasModelTable($class, $table) ? $class::query() : null;
    }

    private function hasModelTable(string $class, string $table): bool
    {
        return class_exists($class) && Schema::hasTable($table);
    }
}
