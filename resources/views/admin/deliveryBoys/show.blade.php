@extends('layouts.admin')

@section('page-title', 'Show Delivery Boy')

@section('content')

@php
    $colors = ['#4F46E5','#0EA5E9','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6'];
    $color = $colors[$deliveryBoy->id % count($colors)];
@endphp

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.delivery-boys.index') }}" class="admin-back-link">
            &larr; {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Delivery Boy Details</h2>
        <p class="admin-page-subtitle">Full delivery staff profile and documents</p>
    </div>

    <div class="show-actions">
        @can('delivery_boy_edit')
            <a href="{{ route('admin.delivery-boys.edit', $deliveryBoy->id) }}" class="btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Edit Delivery Boy
            </a>
        @endcan

        <a href="{{ route('admin.delivery-boys.index') }}" class="btn-outline">
            <i class="fas fa-list"></i>
            Back to list
        </a>

        @can('delivery_boy_delete')
            <form action="{{ route('admin.delivery-boys.destroy', $deliveryBoy->id) }}"
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
                @if($deliveryBoy->profile_image)
                    <img src="{{ $deliveryBoy->profile_image['url'] }}" alt="{{ $deliveryBoy->name }}"
                         class="profile-avatar-lg" style="object-fit:cover;">
                @else
                    <div class="profile-avatar-lg" style="background: {{ $color }};">
                        {{ strtoupper(substr($deliveryBoy->name, 0, 1)) }}
                    </div>
                @endif

                <p class="profile-title">{{ $deliveryBoy->name }}</p>
                <p class="profile-subtitle">{{ $deliveryBoy->mobile ?: $deliveryBoy->email ?: 'No contact added' }}</p>

                @if($deliveryBoy->status)
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
                        <p class="stat-mini-label">Delivery Boy ID</p>
                        <p class="stat-mini-value">#{{ $deliveryBoy->id }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">City</p>
                        <p class="stat-mini-value">{{ $deliveryBoy->city ?: '-' }}</p>
                    </div>

                    <div class="stat-mini" style="grid-column: span 2;">
                        <p class="stat-mini-label">Vehicle</p>
                        <p class="stat-mini-value-sm">
                            {{ $deliveryBoy->vehicle_type ?: '-' }}
                            {{ $deliveryBoy->vehicle_number ? ' / ' . $deliveryBoy->vehicle_number : '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card detail-card-pad">
            <p class="quick-title">Quick Actions</p>

            <div class="quick-list">
                @can('delivery_boy_edit')
                    <a href="{{ route('admin.delivery-boys.edit', $deliveryBoy->id) }}" class="quick-link primary">
                        <i class="fas fa-pencil-alt"></i>
                        Edit Delivery Boy
                    </a>
                @endcan

                <a href="{{ route('admin.delivery-boys.index') }}" class="quick-link">
                    <i class="fas fa-list"></i>
                    Back to list
                </a>
            </div>
        </div>
    </div>

    <div>
        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon"><i class="fas fa-id-card"></i></div>
                <p class="detail-section-title">Personal Information</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">ID</span>
                    <span class="detail-value code-pill">#{{ $deliveryBoy->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">{{ $deliveryBoy->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Mobile</span>
                    <span class="detail-value">{{ $deliveryBoy->mobile ?: '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">{{ $deliveryBoy->email ?: '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    @if($deliveryBoy->status)
                        <span class="status-pill success">Active</span>
                    @else
                        <span class="status-pill warning">Inactive</span>
                    @endif
                </div>
                <div class="detail-row">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">{{ optional($deliveryBoy->created_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon"><i class="fas fa-map-marker-alt"></i></div>
                <p class="detail-section-title">Address Information</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">Address</span>
                    <span class="detail-value">{{ $deliveryBoy->address ?: '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">City</span>
                    <span class="detail-value">{{ $deliveryBoy->city ?: '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Area</span>
                    <span class="detail-value">{{ $deliveryBoy->area ?: '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Pincode</span>
                    <span class="detail-value code-pill">{{ $deliveryBoy->pincode ?: '-' }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon"><i class="fas fa-motorcycle"></i></div>
                <p class="detail-section-title">Vehicle & ID Proof</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">Vehicle Type</span>
                    <span class="detail-value">{{ $deliveryBoy->vehicle_type ?: '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Vehicle Number</span>
                    <span class="detail-value code-pill">{{ $deliveryBoy->vehicle_number ?: '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">ID Proof Type</span>
                    <span class="detail-value">{{ $deliveryBoy->id_proof_type ?: '-' }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-section-head">
                <div class="detail-section-icon"><i class="fas fa-images"></i></div>
                <p class="detail-section-title">Documents Preview</p>
            </div>

            <div class="detail-section-pad-sm">
                <div class="document-grid">
                    <div class="document-card">
                        <p class="document-title">Profile Image</p>
                        @if($deliveryBoy->profile_image)
                            <img src="{{ $deliveryBoy->profile_image['url'] }}" alt="{{ $deliveryBoy->name }}">
                        @else
                            <p class="document-empty">No profile image</p>
                        @endif
                    </div>

                    <div class="document-card">
                        <p class="document-title">ID Proof Image</p>
                        @if($deliveryBoy->id_proof_image)
                            <img src="{{ $deliveryBoy->id_proof_image['url'] }}" alt="ID Proof">
                        @else
                            <p class="document-empty">No ID proof image</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.document-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
    gap:14px;
}
.document-card{
    border:1px solid #E2E8F0;
    border-radius:16px;
    padding:14px;
    background:#fff;
}
.document-card img{
    width:100%;
    aspect-ratio:4 / 3;
    object-fit:cover;
    border-radius:12px;
    border:1px solid #E2E8F0;
}
.document-title{
    margin:0 0 10px;
    font-size:13px;
    font-weight:800;
    color:#0F172A;
}
.document-empty{
    margin:0;
    font-size:13px;
    color:#94A3B8;
}
</style>

@endsection
