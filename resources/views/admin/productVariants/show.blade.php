@extends('layouts.admin')

@section('page-title', 'Show Product Variant')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.product-variants.index') }}" class="admin-back-link">
            ← {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Product Variant Details</h2>

        <p class="admin-page-subtitle">
            Full details for this product size/color variant
        </p>
    </div>

    <div class="show-actions">
        @can('product_variant_edit')
            <a href="{{ route('admin.product-variants.edit', $productVariant->id) }}" class="btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Edit Variant
            </a>
        @endcan

        @can('product_variant_delete')
            <form action="{{ route('admin.product-variants.destroy', $productVariant->id) }}"
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
                    @if(optional($productVariant->product)->main_image)
                        <img src="{{ $productVariant->product->main_image['url'] }}"
                             alt="{{ optional($productVariant->product)->name }}"
                             style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#94A3B8;font-size:54px;">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                    @endif
                </div>

                <p class="profile-title">{{ optional($productVariant->product)->name ?? 'Product Variant' }}</p>
                <p class="profile-subtitle">
                    {{ $productVariant->size ?: '-' }} / {{ $productVariant->color ?: '-' }}
                </p>

                @if($productVariant->status)
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
                        <p class="stat-mini-label">Variant ID</p>
                        <p class="stat-mini-value">#{{ $productVariant->id }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Stock</p>
                        <p class="stat-mini-value">{{ $productVariant->stock_quantity }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Size</p>
                        <p class="stat-mini-value-sm">{{ $productVariant->size ?: '-' }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Color</p>
                        <p class="stat-mini-value-sm">{{ $productVariant->color ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card detail-card-pad">
            <p class="quick-title">Quick Actions</p>

            <div class="quick-list">
                @can('product_variant_edit')
                    <a href="{{ route('admin.product-variants.edit', $productVariant->id) }}" class="quick-link primary">
                        <i class="fas fa-pencil-alt"></i>
                        Edit Variant
                    </a>
                @endcan

                <a href="{{ route('admin.product-variants.index') }}" class="quick-link">
                    <i class="fas fa-list"></i>
                    All Variants
                </a>

                @can('product_show')
                    @if($productVariant->product)
                        <a href="{{ route('admin.products.show', $productVariant->product_id) }}" class="quick-link">
                            <i class="fas fa-tshirt"></i>
                            View Product
                        </a>
                    @endif
                @endcan
            </div>
        </div>
    </div>

    <div>
        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-info-circle"></i>
                </div>

                <p class="detail-section-title">Variant Information</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">ID</span>
                    <span class="detail-value code-pill">#{{ $productVariant->id }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Product</span>
                    <span class="detail-value">{{ optional($productVariant->product)->name ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Size</span>
                    <span class="detail-value">{{ $productVariant->size ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Color</span>
                    <span class="detail-value">{{ $productVariant->color ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">SKU</span>
                    <span class="detail-value code-pill">{{ $productVariant->sku ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Sort Order</span>
                    <span class="detail-value">{{ $productVariant->sort_order }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">
                        {{ optional($productVariant->created_at)->format('d M Y, H:i') ?? '-' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Updated At</span>
                    <span class="detail-value">
                        {{ optional($productVariant->updated_at)->format('d M Y, H:i') ?? '-' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-rupee-sign"></i>
                </div>

                <p class="detail-section-title">Price & Stock</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">Variant Price</span>
                    <span class="detail-value">
                        {{ $productVariant->price ? '₹' . number_format($productVariant->price, 2) : '-' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Discount Price</span>
                    <span class="detail-value">
                        {{ $productVariant->discount_price ? '₹' . number_format($productVariant->discount_price, 2) : '-' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Stock Quantity</span>

                    @if($productVariant->stock_quantity <= 0)
                        <span class="status-pill warning">Out of stock</span>
                    @elseif($productVariant->stock_quantity <= 5)
                        <span class="status-pill warning">{{ $productVariant->stock_quantity }} left</span>
                    @else
                        <span class="status-pill success">{{ $productVariant->stock_quantity }}</span>
                    @endif
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status</span>

                    @if($productVariant->status)
                        <span class="status-pill success">Active</span>
                    @else
                        <span class="status-pill warning">Inactive</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

@endsection