@extends('layouts.admin')

@section('page-title', 'Show Shop')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.shops.index') }}" class="admin-back-link">
            ← {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Shop Details</h2>

        <p class="admin-page-subtitle">Full details for local clothing shop</p>
    </div>

    <div class="show-actions">
        @can('shop_edit')
            <a href="{{ route('admin.shops.edit', $shop->id) }}" class="btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Edit Shop
            </a>
        @endcan

        @can('shop_delete')
            <form action="{{ route('admin.shops.destroy', $shop->id) }}"
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
                <div style="width:100%; max-width:320px; height:240px; margin:0 auto; border-radius:24px; overflow:hidden; border:1px solid #E2E8F0; background:#F8FAFC;">
                    @if($shop->shop_image)
                        <img src="{{ $shop->shop_image['url'] }}" alt="{{ $shop->shop_name }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#94A3B8;font-size:54px;">
                            <i class="fas fa-store"></i>
                        </div>
                    @endif
                </div>

                <p class="profile-title">{{ $shop->shop_name }}</p>
                <p class="profile-subtitle">{{ $shop->owner_name ?: 'No owner name' }}</p>

                @if($shop->status)
                    <span class="status-pill success"><i class="fas fa-check-circle"></i> Active</span>
                @else
                    <span class="status-pill warning"><i class="fas fa-clock"></i> Inactive</span>
                @endif
            </div>

            <div class="detail-section-pad-sm">
                <div class="d-grid gap-2" style="grid-template-columns: 1fr 1fr;">
                    <div class="stat-mini">
                        <p class="stat-mini-label">Shop ID</p>
                        <p class="stat-mini-value">#{{ $shop->id }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Products</p>
                        <p class="stat-mini-value">{{ $shop->products->count() }}</p>
                    </div>

                    <div class="stat-mini" style="grid-column: span 2;">
                        <p class="stat-mini-label">Pincode</p>
                        <p class="stat-mini-value-sm">{{ $shop->pincode ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card detail-card-pad">
            <p class="quick-title">Quick Actions</p>

            <div class="quick-list">
                @can('shop_edit')
                    <a href="{{ route('admin.shops.edit', $shop->id) }}" class="quick-link primary">
                        <i class="fas fa-pencil-alt"></i>
                        Edit Shop
                    </a>
                @endcan

                <a href="{{ route('admin.shops.index') }}" class="quick-link">
                    <i class="fas fa-list"></i>
                    All Shops
                </a>

                @can('shop_create')
                    <a href="{{ route('admin.shops.create') }}" class="quick-link">
                        <i class="fas fa-plus"></i>
                        Add New Shop
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div>
        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-store"></i>
                </div>

                <p class="detail-section-title">Shop Information</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">ID</span>
                    <span class="detail-value code-pill">#{{ $shop->id }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Shop Name</span>
                    <span class="detail-value">{{ $shop->shop_name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Owner Name</span>
                    <span class="detail-value">{{ $shop->owner_name ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Mobile</span>
                    <span class="detail-value">{{ $shop->mobile ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">{{ $shop->email ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Opening Time</span>
                    <span class="detail-value">{{ $shop->opening_time ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Closing Time</span>
                    <span class="detail-value">{{ $shop->closing_time ?: '-' }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>

                <p class="detail-section-title">Location Details</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">Address</span>
                    <span class="detail-value">{{ $shop->address ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">City</span>
                    <span class="detail-value">{{ $shop->city ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Area</span>
                    <span class="detail-value">{{ $shop->area ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Pincode</span>
                    <span class="detail-value code-pill">{{ $shop->pincode ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Latitude</span>
                    <span class="detail-value">{{ $shop->latitude ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Longitude</span>
                    <span class="detail-value">{{ $shop->longitude ?: '-' }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-section-head between">
                <div class="d-flex align-items-center gap-2">
                    <div class="detail-section-icon">
                        <i class="fas fa-box"></i>
                    </div>

                    <p class="detail-section-title">Shop Products</p>
                </div>

                <span class="status-pill success">{{ $shop->products->count() }} products</span>
            </div>

            <div class="detail-section-pad-sm">
                @if($shop->products->count())
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($shop->products->take(12) as $product)
                            <span class="role-tag">{{ $product->name }}</span>
                        @endforeach

                        @if($shop->products->count() > 12)
                            <span class="role-tag">+{{ $shop->products->count() - 12 }} more</span>
                        @endif
                    </div>
                @else
                    <div class="assign-empty">
                        <div class="assign-empty-icon">
                            <i class="fas fa-box-open"></i>
                        </div>

                        <p class="assign-empty-title">No products yet</p>
                        <p class="assign-empty-text">This shop has no products assigned yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection