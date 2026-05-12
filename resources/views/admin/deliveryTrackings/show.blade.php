@extends('layouts.admin')

@section('page-title', 'Show Delivery Tracking')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.delivery-trackings.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Delivery Tracking Details</h2>
        <p class="admin-page-subtitle">Track assignment, route status and COD collection</p>
    </div>
    <div class="show-actions">
        @can('delivery_tracking_edit')
            <a href="{{ route('admin.delivery-trackings.edit', $deliveryTracking->id) }}" class="btn-primary"><i class="fas fa-pencil-alt"></i> Edit Delivery Tracking</a>
        @endcan
        <a href="{{ route('admin.delivery-trackings.index') }}" class="btn-outline"><i class="fas fa-list"></i> Back to list</a>
        @can('delivery_tracking_delete')
            <form action="{{ route('admin.delivery-trackings.destroy', $deliveryTracking->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn-danger"><i class="fas fa-trash-alt"></i> Delete Delivery Tracking</button>
            </form>
        @endcan
    </div>
</div>

<div class="show-grid">
    <div>
        <div class="detail-card mb-3">
            <div class="profile-hero">
                <div class="profile-avatar-lg" style="background:#4F46E5;"><i class="fas fa-route"></i></div>
                <p class="profile-title">{{ $deliveryTracking->tracking_number }}</p>
                <p class="profile-subtitle">{{ optional($deliveryTracking->order)->order_number ?: 'No order linked' }}</p>
                <span class="status-pill {{ in_array($deliveryTracking->status, ['delivered', 'assigned', 'picked_up', 'out_for_delivery']) ? 'success' : 'warning' }}">{{ \App\Models\DeliveryTracking::STATUSES[$deliveryTracking->status] ?? $deliveryTracking->status }}</span>
                <span class="status-pill {{ $deliveryTracking->cod_collected ? 'success' : 'warning' }}">{{ $deliveryTracking->cod_amount > 0 ? ($deliveryTracking->cod_collected ? 'COD Collected' : 'COD Pending') : 'No COD' }}</span>
            </div>
            <div class="detail-section-pad-sm">
                <div class="d-grid gap-2" style="grid-template-columns:1fr 1fr;">
                    <div class="stat-mini"><p class="stat-mini-label">Delivery Boy</p><p class="stat-mini-value-sm">{{ optional($deliveryTracking->deliveryBoy)->name ?: '-' }}</p></div>
                    <div class="stat-mini"><p class="stat-mini-label">Customer</p><p class="stat-mini-value-sm">{{ optional($deliveryTracking->customer)->name ?: optional($deliveryTracking->order)->customer_name ?: '-' }}</p></div>
                </div>
            </div>
        </div>

        @can('delivery_tracking_cod_update')
            @if($deliveryTracking->cod_amount > 0 && ! $deliveryTracking->cod_collected)
                <div class="detail-card detail-card-pad mb-3">
                    <p class="quick-title">COD Collection</p>
                    <form method="POST" action="{{ route('admin.delivery-trackings.markCodCollected', $deliveryTracking->id) }}">
                        @csrf
                        <button type="submit" class="btn-primary"><i class="fas fa-wallet"></i> Mark COD Collected</button>
                    </form>
                </div>
            @endif
        @endcan

        @can('delivery_tracking_status_update')
            <div class="detail-card detail-card-pad mb-3">
                <p class="quick-title">Status Update Form</p>
                <form method="POST" action="{{ route('admin.delivery-trackings.updateStatus', $deliveryTracking->id) }}">
                    @csrf
                    <div class="field-group">
                        <select name="status" class="field-input" required>
                            @foreach(\App\Models\DeliveryTracking::STATUSES as $value => $label)
                                <option value="{{ $value }}" {{ $deliveryTracking->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field-group"><input type="text" name="failure_reason" class="field-input" placeholder="Failure reason"></div>
                    <div class="field-group"><textarea name="delivery_note" rows="2" class="field-input" placeholder="Delivery note"></textarea></div>
                    <div class="field-group"><textarea name="admin_note" rows="2" class="field-input" placeholder="Admin note"></textarea></div>
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Update Status</button>
                </form>
            </div>
        @endcan

        @can('delivery_tracking_assign')
            <div class="detail-card detail-card-pad">
                <p class="quick-title">Assign Delivery Boy Form</p>
                <form method="POST" action="{{ route('admin.delivery-trackings.assignDeliveryBoy', $deliveryTracking->id) }}">
                    @csrf
                    <div class="field-group">
                        <select name="delivery_boy_id" class="field-input" required>
                            @foreach($deliveryBoys as $id => $entry)
                                <option value="{{ $id }}" {{ (string) $deliveryTracking->delivery_boy_id === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field-group"><textarea name="admin_note" rows="2" class="field-input" placeholder="Assignment note"></textarea></div>
                    <button type="submit" class="btn-primary"><i class="fas fa-truck"></i> Assign Delivery Boy</button>
                </form>
            </div>
        @endcan
    </div>

    <div>
        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-receipt"></i></div><p class="detail-section-title">Order & Assignment</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">Order</span><span class="detail-value">{{ optional($deliveryTracking->order)->order_number ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Shop</span><span class="detail-value">{{ optional($deliveryTracking->shop)->shop_name ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Delivery Boy</span><span class="detail-value">{{ optional($deliveryTracking->deliveryBoy)->name ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Customer</span><span class="detail-value">{{ optional($deliveryTracking->customer)->name ?: optional($deliveryTracking->order)->customer_name ?: '-' }}</span></div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-map-marker-alt"></i></div><p class="detail-section-title">Pickup & Delivery Address</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">Pickup</span><span class="detail-value">{{ $deliveryTracking->pickup_address ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Delivery</span><span class="detail-value">{{ $deliveryTracking->delivery_address ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">City / Area</span><span class="detail-value">{{ $deliveryTracking->city ?: '-' }} / {{ $deliveryTracking->area ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Pincode</span><span class="detail-value code-pill">{{ $deliveryTracking->pincode ?: '-' }}</span></div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-wallet"></i></div><p class="detail-section-title">COD Details</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">Amount</span><span class="detail-value code-pill">Rs. {{ number_format($deliveryTracking->cod_amount, 2) }}</span></div>
                <div class="detail-row"><span class="detail-label">Collected</span><span class="detail-value">{{ $deliveryTracking->cod_collected ? 'Yes' : 'No' }}</span></div>
                <div class="detail-row"><span class="detail-label">Collected At</span><span class="detail-value">{{ optional($deliveryTracking->cod_collected_at)->format('d M Y, H:i') ?: '-' }}</span></div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-clock"></i></div><p class="detail-section-title">Status Timestamps</p></div>
            <div class="detail-section-body">
                @foreach(['assigned_at','pickup_pending_at','picked_up_at','out_for_delivery_at','delivered_at','failed_delivery_at','cancelled_at'] as $field)
                    <div class="detail-row"><span class="detail-label">{{ ucwords(str_replace('_', ' ', $field)) }}</span><span class="detail-value">{{ optional($deliveryTracking->{$field})->format('d M Y, H:i') ?: '-' }}</span></div>
                @endforeach
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-sticky-note"></i></div><p class="detail-section-title">Notes</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">Failure Reason</span><span class="detail-value">{{ $deliveryTracking->failure_reason ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Delivery Note</span><span class="detail-value">{{ $deliveryTracking->delivery_note ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Admin Note</span><span class="detail-value">{{ $deliveryTracking->admin_note ?: '-' }}</span></div>
            </div>
        </div>
    </div>
</div>

@endsection
