@extends('layouts.admin')

@section('page-title', 'Show Order')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Order Details</h2>
        <p class="admin-page-subtitle">Full order, payment, delivery and status timeline</p>
    </div>

    <div class="show-actions">
        @can('order_edit')
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn-primary"><i class="fas fa-pencil-alt"></i> Edit Order</a>
        @endcan
        @can('order_delete')
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn-danger"><i class="fas fa-trash-alt"></i> Delete</button>
            </form>
        @endcan
    </div>
</div>

<div class="show-grid">
    <div>
        <div class="detail-card mb-3">
            <div class="profile-hero">
                <div class="profile-avatar-lg" style="background:#4F46E5;"><i class="fas fa-receipt"></i></div>
                <p class="profile-title">{{ $order->order_number }}</p>
                <p class="profile-subtitle">{{ $order->customer_name ?: optional($order->customer)->name ?: '-' }}</p>
                <span class="status-pill success">{{ \App\Models\Order::ORDER_STATUSES[$order->order_status] ?? $order->order_status }}</span>
                <span class="status-pill {{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span>
            </div>
            <div class="detail-section-pad-sm">
                <div class="d-grid gap-2" style="grid-template-columns:1fr 1fr;">
                    <div class="stat-mini"><p class="stat-mini-label">Total</p><p class="stat-mini-value">₹{{ number_format($order->total_amount, 2) }}</p></div>
                    <div class="stat-mini"><p class="stat-mini-label">Try Cloth</p><p class="stat-mini-value">{{ $order->try_cloth_selected ? 'Yes' : 'No' }}</p></div>
                    <div class="stat-mini" style="grid-column:span 2;"><p class="stat-mini-label">Return Eligible</p><p class="stat-mini-value-sm">{{ $order->return_eligible ? 'Allowed' : 'Not allowed' }}</p></div>
                </div>
            </div>
        </div>

        <div class="detail-card detail-card-pad mb-3">
            <p class="quick-title">Quick Actions</p>
            <div class="quick-list">
                @can('order_edit')
                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="quick-link primary"><i class="fas fa-pencil-alt"></i> Edit Order</a>
                @endcan
                <a href="{{ route('admin.orders.index') }}" class="quick-link"><i class="fas fa-list"></i> Back to list</a>
            </div>
        </div>

        @can('order_status_update')
            <div class="detail-card detail-card-pad mb-3">
                <p class="quick-title">Update Status</p>
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                    @csrf
                    <div class="field-group">
                        <select name="order_status" class="field-input">
                            @foreach(\App\Models\Order::ORDER_STATUSES as $value => $label)
                                <option value="{{ $value }}" {{ $order->order_status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field-group"><textarea name="note" rows="2" class="field-input" placeholder="Status note"></textarea></div>
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Update Status</button>
                </form>
            </div>
        @endcan

        @can('order_assign_delivery')
            <div class="detail-card detail-card-pad">
                <p class="quick-title">Assign Delivery Boy</p>
                <form method="POST" action="{{ route('admin.orders.assignDeliveryBoy', $order->id) }}">
                    @csrf
                    <div class="field-group">
                        <select name="delivery_boy_id" class="field-input" required>
                            @foreach($deliveryBoys as $id => $entry)
                                <option value="{{ $id }}" {{ (string) $order->delivery_boy_id === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field-group"><textarea name="note" rows="2" class="field-input" placeholder="Assignment note"></textarea></div>
                    <button type="submit" class="btn-primary"><i class="fas fa-truck"></i> Assign</button>
                </form>
            </div>
        @endcan
    </div>

    <div>
        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-user"></i></div><p class="detail-section-title">Customer & Address Snapshot</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">Customer</span><span class="detail-value">{{ $order->customer_name ?: optional($order->customer)->name ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Mobile</span><span class="detail-value">{{ $order->customer_mobile ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Address</span><span class="detail-value">{{ $order->delivery_address ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">City / Area</span><span class="detail-value">{{ $order->city ?: '-' }} / {{ $order->area ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Pincode</span><span class="detail-value code-pill">{{ $order->pincode ?: '-' }}</span></div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-tshirt"></i></div><p class="detail-section-title">Order Items</p></div>
            <div class="page-card-table">
                <table class="min-w-full">
                    <thead><tr><th>Product</th><th>SKU</th><th>Size/Color</th><th>Price</th><th>Qty</th><th>Total</th><th>Return</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name ?: optional($item->product)->name }}</td>
                                <td>{{ $item->product_sku ?: '-' }}</td>
                                <td>{{ $item->size ?: '-' }} / {{ $item->color ?: '-' }}</td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->total, 2) }}</td>
                                <td>{{ $item->return_eligible ? 'Allowed' : 'No' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-credit-card"></i></div><p class="detail-section-title">Payment Details</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">Method</span><span class="detail-value">{{ strtoupper($order->payment_method ?: '-') }}</span></div>
                <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value">{{ ucfirst($order->payment_status) }}</span></div>
                <div class="detail-row"><span class="detail-label">Subtotal</span><span class="detail-value">₹{{ number_format($order->subtotal, 2) }}</span></div>
                <div class="detail-row"><span class="detail-label">Discount</span><span class="detail-value">₹{{ number_format($order->discount_amount, 2) }}</span></div>
                <div class="detail-row"><span class="detail-label">Delivery</span><span class="detail-value">₹{{ number_format($order->delivery_charge, 2) }}</span></div>
                <div class="detail-row"><span class="detail-label">Tax</span><span class="detail-value">₹{{ number_format($order->tax_amount, 2) }}</span></div>
                <div class="detail-row"><span class="detail-label">Total</span><span class="detail-value code-pill">₹{{ number_format($order->total_amount, 2) }}</span></div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-truck"></i></div><p class="detail-section-title">Delivery Assignment</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">Shop</span><span class="detail-value">{{ optional($order->shop)->shop_name ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Delivery Boy</span><span class="detail-value">{{ optional($order->deliveryBoy)->name ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Assigned At</span><span class="detail-value">{{ optional($order->assigned_at)->format('d M Y, H:i') ?: '-' }}</span></div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-history"></i></div><p class="detail-section-title">Status Timeline</p></div>
            <div class="detail-section-body">
                @forelse($order->statusHistories as $history)
                    <div class="detail-row">
                        <span class="detail-label">{{ optional($history->created_at)->format('d M Y, H:i') }}</span>
                        <span class="detail-value">{{ \App\Models\Order::ORDER_STATUSES[$history->status] ?? $history->status }}{{ $history->note ? ' - ' . $history->note : '' }}</span>
                    </div>
                @empty
                    <div class="assign-empty">No status history</div>
                @endforelse
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-sticky-note"></i></div><p class="detail-section-title">Notes</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">Notes</span><span class="detail-value">{{ $order->notes ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Admin Note</span><span class="detail-value">{{ $order->admin_note ?: '-' }}</span></div>
            </div>
        </div>
    </div>
</div>

@endsection

