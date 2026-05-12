@extends('layouts.admin')

@section('page-title', 'Show Customer')

@section('content')

@php
    $colors = ['#4F46E5','#0EA5E9','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6'];
    $color = $colors[$customer->id % count($colors)];
@endphp

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.customers.index') }}" class="admin-back-link">
            &larr; {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Customer Details</h2>

        <p class="admin-page-subtitle">
            Full customer details and delivery addresses
        </p>
    </div>

    <div class="show-actions">
        @can('customer_edit')
            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Edit Customer
            </a>
        @endcan

        @can('customer_address_create')
            <a href="{{ route('admin.customer-addresses.create', ['customer_id' => $customer->id]) }}" class="btn-outline btn-outline-edit">
                <i class="fas fa-map-marker-alt"></i>
                Add Address
            </a>
        @endcan

        @can('customer_delete')
            <form action="{{ route('admin.customers.destroy', $customer->id) }}"
                  method="POST"
                  onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
                @method('DELETE')
                @csrf

                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash-alt"></i>
                    Delete
                </button>
            </form>
        @endcan
    </div>
</div>

<div class="show-grid">

    <div>
        <div class="detail-card mb-3">
            <div class="profile-hero">
                <div class="profile-avatar-lg" style="background: {{ $color }};">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>

                <p class="profile-title">{{ $customer->name }}</p>
                <p class="profile-subtitle">{{ $customer->mobile ?: $customer->display_email ?: 'No contact added' }}</p>

                @if($customer->status)
                    <span class="status-pill success">
                        <i class="fas fa-check-circle"></i>
                        Active
                    </span>
                @else
                    <span class="status-pill warning">
                        <i class="fas fa-clock"></i>
                        Inactive
                    </span>
                @endif
            </div>

            <div class="detail-section-pad-sm">
                <div class="d-grid gap-2" style="grid-template-columns: 1fr 1fr;">
                    <div class="stat-mini">
                        <p class="stat-mini-label">Customer ID</p>
                        <p class="stat-mini-value">#{{ $customer->id }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Addresses</p>
                        <p class="stat-mini-value">{{ $customer->addresses->count() }}</p>
                    </div>

                    <div class="stat-mini" style="grid-column: span 2;">
                        <p class="stat-mini-label">Member Since</p>
                        <p class="stat-mini-value-sm">
                            {{ optional($customer->created_at)->format('d M Y') ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card detail-card-pad">
            <p class="quick-title">Quick Actions</p>

            <div class="quick-list">
                @can('customer_edit')
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="quick-link primary">
                        <i class="fas fa-pencil-alt"></i>
                        Edit Customer
                    </a>
                @endcan

                @can('customer_address_create')
                    <a href="{{ route('admin.customer-addresses.create', ['customer_id' => $customer->id]) }}" class="quick-link">
                        <i class="fas fa-map-marker-alt"></i>
                        Add Address
                    </a>
                @endcan

                <a href="{{ route('admin.customers.index') }}" class="quick-link">
                    <i class="fas fa-list"></i>
                    All Customers
                </a>
            </div>
        </div>
    </div>

    <div>
        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-id-card"></i>
                </div>

                <p class="detail-section-title">Customer Information</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">ID</span>
                    <span class="detail-value code-pill">#{{ $customer->id }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">{{ $customer->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Mobile</span>
                    <span class="detail-value">{{ $customer->mobile ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">{{ $customer->display_email ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Email Verified</span>
                    <span class="detail-value">
                        {{ $customer->email_verified_at ?: 'No' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Mobile Verified</span>
                    <span class="detail-value">
                        {{ $customer->mobile_verified_at ? optional($customer->mobile_verified_at)->format('d M Y, H:i') : 'No' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">{{ optional($customer->created_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Updated At</span>
                    <span class="detail-value">{{ optional($customer->updated_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-section-head between">
                <div class="d-flex align-items-center gap-2">
                    <div class="detail-section-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>

                    <p class="detail-section-title">Delivery Addresses</p>
                </div>

                <span class="status-pill success">
                    {{ $customer->addresses->count() }} addresses
                </span>
            </div>

            <div class="detail-section-pad-sm">
                @if($customer->addresses->count())
                    <div class="address-list-grid">
                        @foreach($customer->addresses as $address)
                            <div class="address-mini-card">
                                <div class="address-mini-head">
                                    <div>
                                        <p class="address-mini-title">{{ $address->name ?: $customer->name }}</p>
                                        <p class="address-mini-subtitle">{{ $address->mobile ?: $customer->mobile }}</p>
                                    </div>

                                    @if($address->is_default)
                                        <span class="status-pill success">Default</span>
                                    @endif
                                </div>

                                <p class="address-mini-text">
                                    {{ $address->address }}
                                    {{ $address->landmark ? ', ' . $address->landmark : '' }}
                                    {{ $address->area ? ', ' . $address->area : '' }}
                                    {{ $address->city ? ', ' . $address->city : '' }}
                                    {{ $address->pincode ? ' - ' . $address->pincode : '' }}
                                </p>

                                <div class="action-row mt-2">
                                    @can('customer_address_show')
                                        <a href="{{ route('admin.customer-addresses.show', $address->id) }}" class="btn-outline">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </a>
                                    @endcan

                                    @can('customer_address_edit')
                                        <a href="{{ route('admin.customer-addresses.edit', $address->id) }}" class="btn-outline btn-outline-edit">
                                            <i class="fas fa-pencil-alt"></i>
                                            Edit
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="assign-empty">
                        <div class="assign-empty-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>

                        <p class="assign-empty-title">No addresses added</p>
                        <p class="assign-empty-text">Add delivery address for nearest shop assignment.</p>

                        @can('customer_address_create')
                            <a href="{{ route('admin.customer-addresses.create', ['customer_id' => $customer->id]) }}" class="btn-primary mt-3">
                                <i class="fas fa-plus"></i>
                                Add Address
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<style>
.address-list-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));
    gap:14px;
}
.address-mini-card{
    border:1px solid #E2E8F0;
    border-radius:18px;
    padding:16px;
    background:#fff;
    box-shadow:0 10px 24px rgba(15,23,42,.04);
}
.address-mini-head{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
    margin-bottom:10px;
}
.address-mini-title{
    margin:0;
    font-size:14px;
    font-weight:800;
    color:#0F172A;
}
.address-mini-subtitle{
    margin:2px 0 0;
    font-size:12px;
    color:#64748B;
}
.address-mini-text{
    margin:0;
    font-size:13px;
    line-height:1.6;
    color:#475569;
}
</style>

@endsection
