@extends('layouts.admin')

@section('page-title', 'Delivery Zone Details')

@section('content')
<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.delivery-zones.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">{{ $deliveryZone->area ?: $deliveryZone->city }}</h2>
        <p class="admin-page-subtitle">{{ $deliveryZone->city }} - {{ $deliveryZone->pincode }}</p>
    </div>
    @can('delivery_zone_edit')
        <a href="{{ route('admin.delivery-zones.edit', $deliveryZone) }}" class="btn-primary"><i class="fas fa-pencil-alt"></i> Edit Zone</a>
    @endcan
</div>

<div class="page-card">
    <div class="page-card-header"><p class="page-card-title">Zone Details</p></div>
    <div class="show-grid">
        <div class="show-item"><span>Shop</span><strong>{{ optional($deliveryZone->shop)->shop_name ?? 'All Shops' }}</strong></div>
        <div class="show-item"><span>Delivery Window</span><strong>{{ $deliveryZone->min_delivery_minutes }}-{{ $deliveryZone->max_delivery_minutes }} minutes</strong></div>
        <div class="show-item"><span>Delivery Charge</span><strong>Rs {{ $deliveryZone->delivery_charge }}</strong></div>
        <div class="show-item"><span>Free Delivery Above</span><strong>{{ $deliveryZone->free_delivery_min_amount ? 'Rs ' . $deliveryZone->free_delivery_min_amount : 'Not set' }}</strong></div>
        <div class="show-item"><span>Try First</span><strong>{{ $deliveryZone->try_first_enabled ? 'Enabled' : 'Disabled' }}</strong></div>
        <div class="show-item"><span>Trial Wait</span><strong>{{ $deliveryZone->trial_wait_minutes }} minutes</strong></div>
        <div class="show-item"><span>COD</span><strong>{{ $deliveryZone->cod_enabled ? 'Enabled' : 'Disabled' }}</strong></div>
        <div class="show-item"><span>Status</span><strong>{{ $deliveryZone->status ? 'Active' : 'Inactive' }}</strong></div>
    </div>
</div>
@endsection
