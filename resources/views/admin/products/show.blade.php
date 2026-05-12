@extends('layouts.admin')

@section('page-title', 'Show Product')

@section('content')

@php
    $variantStock = $product->variants->sum('stock_quantity');
    $totalStock = $product->variants->count() ? $variantStock : $product->stock_quantity;
@endphp

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.products.index') }}" class="admin-back-link">
            ← {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Product Details</h2>

        <p class="admin-page-subtitle">
            Full details for this clothing product, variants and images
        </p>
    </div>

    <div class="show-actions">
        @can('product_edit')
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Edit Product
            </a>
        @endcan

        @can('product_delete')
            <form action="{{ route('admin.products.destroy', $product->id) }}"
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
                <div style="width:100%; max-width:320px; height:260px; margin:0 auto; border-radius:24px; overflow:hidden; border:1px solid #E2E8F0; background:#F8FAFC;">
                    @if($product->main_image)
                        <img src="{{ $product->main_image['url'] }}"
                             alt="{{ $product->name }}"
                             style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#94A3B8;font-size:54px;">
                            <i class="fas fa-tshirt"></i>
                        </div>
                    @endif
                </div>

                <p class="profile-title">{{ $product->name }}</p>
                <p class="profile-subtitle">SKU: {{ $product->sku ?: '-' }}</p>

                @if($product->status)
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
                        <p class="stat-mini-label">Price</p>
                        <p class="stat-mini-value">₹{{ number_format($product->price, 2) }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Total Stock</p>
                        <p class="stat-mini-value">{{ $totalStock }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Variants</p>
                        <p class="stat-mini-value-sm">{{ $product->variants->count() }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Gallery</p>
                        <p class="stat-mini-value-sm">
                            {{ $product->gallery_images ? count($product->gallery_images) : 0 }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card detail-card-pad">
            <p class="quick-title">Quick Actions</p>

            <div class="quick-list">
                @can('product_edit')
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="quick-link primary">
                        <i class="fas fa-pencil-alt"></i>
                        Edit Product
                    </a>
                @endcan

                <a href="{{ route('admin.products.index') }}" class="quick-link">
                    <i class="fas fa-list"></i>
                    All Products
                </a>

                @can('product_create')
                    <a href="{{ route('admin.products.create') }}" class="quick-link">
                        <i class="fas fa-plus"></i>
                        Add New Product
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div>
        {{-- PRODUCT INFORMATION --}}
        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-info-circle"></i>
                </div>

                <p class="detail-section-title">Product Information</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">ID</span>
                    <span class="detail-value code-pill">#{{ $product->id }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">{{ $product->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Slug</span>
                    <span class="detail-value code-pill">{{ $product->slug }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">SKU</span>
                    <span class="detail-value code-pill">{{ $product->sku ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Shop</span>
                    <span class="detail-value">{{ optional($product->shop)->shop_name ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Category</span>
                    <span class="detail-value">{{ optional($product->category)->name ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Brand</span>
                    <span class="detail-value">{{ $product->brand ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Fabric</span>
                    <span class="detail-value">{{ $product->fabric ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">
                        {{ optional($product->created_at)->format('d M Y, H:i') ?? '-' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Updated At</span>
                    <span class="detail-value">
                        {{ optional($product->updated_at)->format('d M Y, H:i') ?? '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- PRICE & RULES --}}
        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-rupee-sign"></i>
                </div>

                <p class="detail-section-title">Price, Stock & Rules</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">Regular Price</span>
                    <span class="detail-value">₹{{ number_format($product->price, 2) }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Discount Price</span>
                    <span class="detail-value">
                        {{ $product->discount_price ? '₹' . number_format($product->discount_price, 2) : '-' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Base Stock</span>
                    <span class="detail-value">{{ $product->stock_quantity }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Variant Stock</span>
                    <span class="detail-value">{{ $variantStock }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Total Stock</span>

                    @if($totalStock <= 0)
                        <span class="status-pill warning">Out of stock</span>
                    @elseif($totalStock <= 5)
                        <span class="status-pill warning">{{ $totalStock }} left</span>
                    @else
                        <span class="status-pill success">{{ $totalStock }}</span>
                    @endif
                </div>

                <div class="detail-row">
                    <span class="detail-label">Try Cloth Available</span>

                    @if($product->try_cloth_available)
                        <span class="status-pill success">Yes</span>
                    @else
                        <span class="status-pill warning">No</span>
                    @endif
                </div>

                <div class="detail-row">
                    <span class="detail-label">Return Available</span>

                    @if($product->return_available)
                        <span class="status-pill success">Allowed</span>
                    @else
                        <span class="status-pill warning">No Return</span>
                    @endif
                </div>

                <div class="detail-row">
                    <span class="detail-label">Featured</span>

                    @if($product->is_featured)
                        <span class="status-pill success">Featured</span>
                    @else
                        <span class="status-pill warning">Normal</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- PRODUCT VARIANTS --}}
        <div class="detail-card mb-3">
            <div class="detail-section-head between">
                <div class="d-flex align-items-center gap-2">
                    <div class="detail-section-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>

                    <p class="detail-section-title">Product Variants</p>
                </div>

                <span class="status-pill success">
                    {{ $product->variants->count() }} variants
                </span>
            </div>

            <div class="detail-section-pad-sm">
                @if($product->variants->count())
                    <div class="page-card-table" style="border-radius:16px; border:1px solid #E2E8F0;">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($product->variants as $variant)
                                    <tr>
                                        <td>
                                            <span class="role-tag">{{ $variant->size ?: '-' }}</span>
                                        </td>

                                        <td>
                                            <span class="role-tag">{{ $variant->color ?: '-' }}</span>
                                        </td>

                                        <td>
                                            <span class="code-pill">{{ $variant->sku ?: '-' }}</span>
                                        </td>

                                        <td>
                                            @if($variant->price)
                                                <p class="table-main-text">
                                                    ₹{{ number_format($variant->price, 2) }}
                                                </p>

                                                @if($variant->discount_price)
                                                    <p class="table-sub-text">
                                                        Sale: ₹{{ number_format($variant->discount_price, 2) }}
                                                    </p>
                                                @endif
                                            @else
                                                <span class="table-sub-text">Product Price</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($variant->stock_quantity <= 0)
                                                <span class="status-pill warning">Out</span>
                                            @elseif($variant->stock_quantity <= 5)
                                                <span class="status-pill warning">{{ $variant->stock_quantity }} left</span>
                                            @else
                                                <span class="status-pill success">{{ $variant->stock_quantity }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($variant->status)
                                                <span class="status-pill success">Active</span>
                                            @else
                                                <span class="status-pill warning">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="assign-empty">
                        <div class="assign-empty-icon">
                            <i class="fas fa-sliders-h"></i>
                        </div>

                        <p class="assign-empty-title">No variants added</p>
                        <p class="assign-empty-text">This product has no size/color variants yet.</p>

                        @can('product_edit')
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-primary mt-3">
                                <i class="fas fa-plus"></i>
                                Add Variants
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>

        {{-- DESCRIPTION --}}
        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-align-left"></i>
                </div>

                <p class="detail-section-title">Description</p>
            </div>

            <div class="detail-section-pad-sm">
                <p class="detail-value" style="display:block; margin-bottom:14px;">
                    <strong>Short Description:</strong><br>
                    {{ $product->short_description ?: '-' }}
                </p>

                <p class="detail-value" style="display:block;">
                    <strong>Full Description:</strong><br>
                    {!! nl2br(e($product->description ?: '-')) !!}
                </p>
            </div>
        </div>

        {{-- GALLERY --}}
        <div class="detail-card">
            <div class="detail-section-head between">
                <div class="d-flex align-items-center gap-2">
                    <div class="detail-section-icon">
                        <i class="fas fa-images"></i>
                    </div>

                    <p class="detail-section-title">Gallery Images</p>
                </div>

                <span class="status-pill success">
                    {{ $product->gallery_images ? count($product->gallery_images) : 0 }} images
                </span>
            </div>

            <div class="detail-section-pad-sm">
                @if($product->gallery_images && count($product->gallery_images))
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(120px, 1fr)); gap:14px;">
                        @foreach($product->gallery_images as $image)
                            <a href="{{ $image['url'] }}" target="_blank" style="display:block;">
                                <img src="{{ $image['url'] }}"
                                     alt="{{ $image['name'] }}"
                                     style="width:100%; height:110px; object-fit:cover; border-radius:14px; border:1px solid #E2E8F0;">
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="assign-empty">
                        <div class="assign-empty-icon">
                            <i class="fas fa-images"></i>
                        </div>

                        <p class="assign-empty-title">No gallery images</p>
                        <p class="assign-empty-text">This product has no gallery images yet.</p>

                        @can('product_edit')
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-primary mt-3">
                                <i class="fas fa-plus"></i>
                                Add Images
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

@endsection