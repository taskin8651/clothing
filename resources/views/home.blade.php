@extends('layouts.admin')

@section('styles')
<style>
    .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 20px; }
    .dashboard-split { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; margin-bottom: 20px; }
    .dashboard-table-wrap { overflow-x: auto; }
    .dashboard-table { width: 100%; border-collapse: collapse; }
    .dashboard-table th { background: #F8FAFC; color: #64748B; font-size: 12px; font-weight: 700; padding: 12px 14px; text-align: left; text-transform: uppercase; }
    .dashboard-table td { border-top: 1px solid #EEF2F7; color: #334155; font-size: 13px; padding: 12px 14px; vertical-align: middle; }
    .dashboard-table tr:hover td { background: #FBFCFE; }
    .dashboard-section { margin-bottom: 22px; }
    .table-main-text { color: #0F172A; font-weight: 700; margin: 0; }
    .table-sub-text { color: #64748B; font-size: 12px; margin: 2px 0 0; }
    .status-pill { align-items: center; border-radius: 999px; display: inline-flex; font-size: 12px; font-weight: 700; gap: 6px; padding: 4px 10px; text-transform: capitalize; white-space: nowrap; }
    .status-dot { border-radius: 999px; display: inline-block; height: 7px; width: 7px; }
    .status-success { background: #DCFCE7; color: #166534; }
    .status-warning { background: #FEF3C7; color: #92400E; }
    .status-danger { background: #FEE2E2; color: #991B1B; }
    .status-info { background: #E0F2FE; color: #075985; }
    .status-muted { background: #F1F5F9; color: #475569; }
    .status-success .status-dot { background: #16A34A; }
    .status-warning .status-dot { background: #F59E0B; }
    .status-danger .status-dot { background: #DC2626; }
    .status-info .status-dot { background: #0284C7; }
    .status-muted .status-dot { background: #64748B; }
    .code-pill { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 999px; color: #334155; display: inline-flex; font-size: 12px; font-weight: 700; padding: 4px 9px; white-space: nowrap; }
    .quick-link { color: #4F46E5; font-size: 13px; font-weight: 700; text-decoration: none; white-space: nowrap; }
    .quick-list { display: flex; flex-wrap: wrap; gap: 8px; }
    .metric-note { color: #64748B; font-size: 12px; margin-top: 5px; }
    @media (max-width: 900px) { .dashboard-split { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
@php
    $money = fn ($amount) => 'Rs. ' . number_format((float) $amount, 2);
    $number = fn ($amount) => number_format((float) $amount);
    $statusClass = function ($status) {
        return match ($status) {
            'paid', 'delivered', 'approved', 'assigned', 'picked_up', 'out_for_delivery' => 'status-success',
            'pending', 'requested', 'confirmed', 'packed', 'pickup_pending', 'refunded', 'returned' => 'status-warning',
            'failed', 'failed_delivery', 'rejected', 'cancelled' => 'status-danger',
            'online', 'cod' => 'status-info',
            default => 'status-muted',
        };
    };
    $statusLabel = fn ($status) => $status ? ucwords(str_replace('_', ' ', $status)) : 'Not Set';
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Dashboard</h1>
        <p class="admin-page-subtitle">Marketplace overview, orders, payments, deliveries and returns</p>
    </div>
    <div class="quick-list">
        @can('order_access')
            <a href="{{ route('admin.orders.index') }}" class="btn-outline">Orders</a>
        @endcan
        @can('payment_access')
            <a href="{{ route('admin.payments.index') }}" class="btn-outline">Payments</a>
        @endcan
    </div>
</div>

<div class="stats-grid dashboard-section">
    <div class="stat-card">
        <div class="stat-label">Total Orders</div>
        <div class="stat-value">{{ $number($total_orders) }}</div>
        <div class="metric-note">{{ $number($confirmed_orders) }} confirmed</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Today Orders</div>
        <div class="stat-value">{{ $number($today_orders) }}</div>
        <div class="metric-note">{{ now()->format('d M Y') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">{{ $money($total_revenue) }}</div>
        <div class="metric-note">{{ $money($current_month_revenue) }} this month</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending Payments</div>
        <div class="stat-value">{{ $number($pending_payments) }}</div>
        <div class="metric-note">{{ $money($cod_pending_amount) }} COD pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Delivered Orders</div>
        <div class="stat-value">{{ $number($delivered_orders) }}</div>
        <div class="metric-note">{{ $number($cancelled_orders) }} cancelled</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Return Requests</div>
        <div class="stat-value">{{ $number($total_return_requests) }}</div>
        <div class="metric-note">{{ $number($requested_returns) }} requested</div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">Sales & Payment Summary</h2>
                <p class="page-card-note">Revenue and payment health</p>
            </div>
        </div>
        <div class="quick-list">
            <span class="code-pill">Paid Revenue: {{ $money($total_revenue) }}</span>
            <span class="code-pill">Today: {{ $money($today_revenue) }}</span>
            <span class="code-pill">COD Pending: {{ $money($cod_pending_amount) }}</span>
            <span class="code-pill">Online Paid: {{ $money($online_paid_amount) }}</span>
            <span class="code-pill">Refunded: {{ $money($refunded_amount) }}</span>
        </div>
    </div>

    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">Operations Summary</h2>
                <p class="page-card-note">Delivery pipeline status</p>
            </div>
        </div>
        <div class="quick-list">
            <span class="code-pill">Assigned: {{ $number($assigned_deliveries) }}</span>
            <span class="code-pill">Out For Delivery: {{ $number($out_for_delivery_count) }}</span>
            <span class="code-pill">Delivered: {{ $number($delivered_deliveries) }}</span>
            <span class="code-pill">Failed: {{ $number($failed_deliveries) }}</span>
            <span class="code-pill">COD Pending: {{ $number($cod_pending_deliveries) }}</span>
        </div>
    </div>

    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">Inventory Summary</h2>
                <p class="page-card-note">Product and stock overview</p>
            </div>
        </div>
        <div class="quick-list">
            <span class="code-pill">Products: {{ $number($total_products) }}</span>
            <span class="code-pill">Active: {{ $number($active_products) }}</span>
            <span class="code-pill">Variants: {{ $number($total_variants) }}</span>
            <span class="code-pill">Low Stock: {{ $number($low_stock_products) }}</span>
            <span class="code-pill">Out Of Stock: {{ $number($out_of_stock_products) }}</span>
        </div>
    </div>

    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">Customers & Finance</h2>
                <p class="page-card-note">Customer, invoice and receipt totals</p>
            </div>
        </div>
        <div class="quick-list">
            <span class="code-pill">Customers: {{ $number($total_customers) }}</span>
            <span class="code-pill">Active: {{ $number($active_customers) }}</span>
            <span class="code-pill">With Address: {{ $number($customers_with_addresses) }}</span>
            <span class="code-pill">Due: {{ $money($total_due_amount) }}</span>
            <span class="code-pill">Received: {{ $money($total_received_amount) }}</span>
        </div>
    </div>
</div>

<div class="dashboard-split">
    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">Recent Orders</h2>
                <p class="page-card-note">Latest 8 marketplace orders</p>
            </div>
            @can('order_access')
                <a href="{{ route('admin.orders.index') }}" class="quick-link">View all</a>
            @endcan
        </div>
        <div class="page-card-table dashboard-table-wrap">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_orders as $order)
                        <tr>
                            <td>
                                <p class="table-main-text">{{ $order->order_number }}</p>
                                <p class="table-sub-text">{{ optional($order->shop)->name ?? 'No shop' }}</p>
                            </td>
                            <td>{{ $order->customer_name ?: optional($order->customer)->name ?: 'Customer' }}</td>
                            <td>{{ $money($order->total_amount) }}</td>
                            <td><span class="status-pill {{ $statusClass($order->payment_status) }}"><span class="status-dot"></span>{{ $statusLabel($order->payment_status) }}</span></td>
                            <td><span class="status-pill {{ $statusClass($order->order_status) }}"><span class="status-dot"></span>{{ $statusLabel($order->order_status) }}</span></td>
                            <td>{{ optional($order->created_at)->format('d M Y') }}</td>
                            <td>
                                @can('order_show')
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-outline">Open</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No recent orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">Recent Payments</h2>
                <p class="page-card-note">Latest transaction records</p>
            </div>
            @can('payment_access')
                <a href="{{ route('admin.payments.index') }}" class="quick-link">View all</a>
            @endcan
        </div>
        <div class="page-card-table dashboard-table-wrap">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_payments as $payment)
                        <tr>
                            <td>{{ optional($payment->order)->order_number ?? 'No order' }}</td>
                            <td><span class="code-pill">{{ strtoupper($payment->payment_method ?? 'N/A') }}</span></td>
                            <td>{{ $money($payment->amount) }}</td>
                            <td><span class="status-pill {{ $statusClass($payment->status) }}"><span class="status-dot"></span>{{ $statusLabel($payment->status) }}</span></td>
                            <td>{{ optional($payment->paid_at)->format('d M Y') ?: '-' }}</td>
                            <td>
                                @can('payment_show')
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn-outline">Open</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No recent payments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="dashboard-split">
    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">Recent Deliveries</h2>
                <p class="page-card-note">Delivery tracking movement</p>
            </div>
            @can('delivery_tracking_access')
                <a href="{{ route('admin.delivery-trackings.index') }}" class="quick-link">View all</a>
            @endcan
        </div>
        <div class="page-card-table dashboard-table-wrap">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Tracking Number</th>
                        <th>Order</th>
                        <th>Delivery Boy</th>
                        <th>Status</th>
                        <th>COD</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_delivery_trackings as $tracking)
                        <tr>
                            <td>{{ $tracking->tracking_number }}</td>
                            <td>{{ optional($tracking->order)->order_number ?? 'No order' }}</td>
                            <td>{{ optional($tracking->deliveryBoy)->name ?? 'Unassigned' }}</td>
                            <td><span class="status-pill {{ $statusClass($tracking->status) }}"><span class="status-dot"></span>{{ $statusLabel($tracking->status) }}</span></td>
                            <td>{{ $tracking->cod_amount > 0 ? ($tracking->cod_collected ? 'Collected' : $money($tracking->cod_amount)) : '-' }}</td>
                            <td>
                                @can('delivery_tracking_show')
                                    <a href="{{ route('admin.delivery-trackings.show', $tracking) }}" class="btn-outline">Open</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No recent deliveries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">Recent Returns</h2>
                <p class="page-card-note">Return request queue</p>
            </div>
            @can('return_request_access')
                <a href="{{ route('admin.return-requests.index') }}" class="quick-link">View all</a>
            @endcan
        </div>
        <div class="page-card-table dashboard-table-wrap">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Return Number</th>
                        <th>Order</th>
                        <th>Product</th>
                        <th>Refund</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_return_requests as $returnRequest)
                        <tr>
                            <td>{{ $returnRequest->return_number }}</td>
                            <td>{{ optional($returnRequest->order)->order_number ?? 'No order' }}</td>
                            <td>
                                <p class="table-main-text">{{ $returnRequest->product_name ?: optional($returnRequest->orderItem)->product_name ?: 'Product' }}</p>
                                <p class="table-sub-text">{{ trim(($returnRequest->size ?: '') . ' ' . ($returnRequest->color ?: '')) ?: '-' }}</p>
                            </td>
                            <td>{{ $money($returnRequest->refund_amount) }}</td>
                            <td><span class="status-pill {{ $statusClass($returnRequest->status) }}"><span class="status-dot"></span>{{ $statusLabel($returnRequest->status) }}</span></td>
                            <td>
                                @can('return_request_show')
                                    <a href="{{ route('admin.return-requests.show', $returnRequest) }}" class="btn-outline">Open</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No recent returns found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="page-card dashboard-section">
    <div class="page-card-header">
        <div>
            <h2 class="page-card-title">Low Stock Items</h2>
            <p class="page-card-note">Products and variants needing stock attention</p>
        </div>
        @can('product_access')
            <a href="{{ route('admin.products.index') }}" class="quick-link">Products</a>
        @endcan
    </div>
    <div class="page-card-table dashboard-table-wrap">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Variant</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($low_stock_items as $item)
                    <tr>
                        <td>{{ $item['product'] }}</td>
                        <td><span class="code-pill">{{ $item['variant'] ?: '-' }}</span></td>
                        <td><span class="status-pill {{ $item['stock'] <= 0 ? 'status-danger' : 'status-warning' }}"><span class="status-dot"></span>{{ $number($item['stock']) }}</span></td>
                        <td>
                            @can('product_show')
                                <a href="{{ $item['url'] }}" class="btn-outline">Open</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">No low stock items found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
