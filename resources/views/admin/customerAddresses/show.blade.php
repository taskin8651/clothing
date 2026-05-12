@extends('layouts.admin')

@section('page-title', 'Show Customer Address')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.customer-addresses.index') }}" class="admin-back-link">
            &larr; {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Customer Address Details</h2>

        <p class="admin-page-subtitle">
            Full delivery address and location information
        </p>
    </div>

    <div class="show-actions">
        @can('customer_address_edit')
            <a href="{{ route('admin.customer-addresses.edit', $customerAddress->id) }}" class="btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Edit Address
            </a>
        @endcan

        @can('customer_address_delete')
            <form action="{{ route('admin.customer-addresses.destroy', $customerAddress->id) }}"
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
                <div class="profile-avatar-lg" style="background:#4F46E5;">
                    <i class="fas fa-map-marker-alt"></i>
                </div>

                <p class="profile-title">{{ optional($customerAddress->customer)->name ?? 'Customer Address' }}</p>
                <p class="profile-subtitle">
                    {{ $customerAddress->city ?: '-' }}
                    {{ $customerAddress->pincode ? ' - ' . $customerAddress->pincode : '' }}
                </p>

                @if($customerAddress->is_default)
                    <span class="status-pill success">
                        <i class="fas fa-check-circle"></i>
                        Default Address
                    </span>
                @elseif($customerAddress->status)
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
                        <p class="stat-mini-label">Address ID</p>
                        <p class="stat-mini-value">#{{ $customerAddress->id }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Pincode</p>
                        <p class="stat-mini-value">{{ $customerAddress->pincode ?: '-' }}</p>
                    </div>

                    <div class="stat-mini" style="grid-column: span 2;">
                        <p class="stat-mini-label">Customer</p>
                        <p class="stat-mini-value-sm">{{ optional($customerAddress->customer)->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card detail-card-pad">
            <p class="quick-title">Quick Actions</p>

            <div class="quick-list">
                @can('customer_address_edit')
                    <a href="{{ route('admin.customer-addresses.edit', $customerAddress->id) }}" class="quick-link primary">
                        <i class="fas fa-pencil-alt"></i>
                        Edit Address
                    </a>
                @endcan

                @if($customerAddress->customer)
                    @can('customer_show')
                        <a href="{{ route('admin.customers.show', $customerAddress->customer_id) }}" class="quick-link">
                            <i class="fas fa-user"></i>
                            View Customer
                        </a>
                    @endcan
                @endif

                <a href="{{ route('admin.customer-addresses.index') }}" class="quick-link">
                    <i class="fas fa-list"></i>
                    All Addresses
                </a>
            </div>
        </div>
    </div>

    <div>
        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-user"></i>
                </div>

                <p class="detail-section-title">Customer & Receiver</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">Customer</span>
                    <span class="detail-value">{{ optional($customerAddress->customer)->name ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Customer Mobile</span>
                    <span class="detail-value">{{ optional($customerAddress->customer)->mobile ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Receiver Name</span>
                    <span class="detail-value">{{ $customerAddress->name ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Receiver Mobile</span>
                    <span class="detail-value">{{ $customerAddress->mobile ?: '-' }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-map"></i>
                </div>

                <p class="detail-section-title">Address Information</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">Address</span>
                    <span class="detail-value">{{ $customerAddress->address ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Landmark</span>
                    <span class="detail-value">{{ $customerAddress->landmark ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">City</span>
                    <span class="detail-value">{{ $customerAddress->city ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Area</span>
                    <span class="detail-value">{{ $customerAddress->area ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Pincode</span>
                    <span class="detail-value code-pill">{{ $customerAddress->pincode ?: '-' }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-location-arrow"></i>
                </div>

                <p class="detail-section-title">Map & Status</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">Latitude</span>
                    <span class="detail-value">{{ $customerAddress->latitude ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Longitude</span>
                    <span class="detail-value">{{ $customerAddress->longitude ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Default Address</span>

                    @if($customerAddress->is_default)
                        <span class="status-pill success">Yes</span>
                    @else
                        <span class="status-pill warning">No</span>
                    @endif
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status</span>

                    @if($customerAddress->status)
                        <span class="status-pill success">Active</span>
                    @else
                        <span class="status-pill warning">Inactive</span>
                    @endif
                </div>

                <div class="detail-row">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">{{ optional($customerAddress->created_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Updated At</span>
                    <span class="detail-value">{{ optional($customerAddress->updated_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
