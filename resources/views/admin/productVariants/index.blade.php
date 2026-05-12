@extends('layouts.admin')

@section('page-title', 'Product Variants')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Product Variants</h2>
        <p class="admin-page-subtitle">
            Manage size, color, SKU and stock for clothing products
        </p>
    </div>

    @can('product_variant_create')
        <a href="{{ route('admin.product-variants.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Add Variant
        </a>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card">
        <p class="stat-label">Total Variants</p>
        <p class="stat-value">{{ $productVariants->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Active</p>
        <p class="stat-value">{{ $productVariants->where('status', 1)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Low Stock</p>
        <p class="stat-value">{{ $productVariants->where('stock_quantity', '<=', 5)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Out of Stock</p>
        <p class="stat-value">{{ $productVariants->where('stock_quantity', 0)->count() }}</p>
    </div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Product Variants</p>

        <span class="page-card-note">
            <i class="fas fa-info-circle"></i>
            Size/color wise inventory
        </span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-ProductVariant">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($productVariants as $variant)
                    <tr data-entry-id="{{ $variant->id }}">
                        <td></td>

                        <td>
                            <span class="id-text">#{{ $variant->id }}</span>
                        </td>

                        <td>
                            <div class="inline-flex-center">
                                <div class="avatar-circle" style="background:#F8FAFC; border:1px solid #E2E8F0; overflow:hidden;">
                                    @if(optional($variant->product)->main_image)
                                        <img src="{{ $variant->product->main_image['url'] }}"
                                             alt="{{ optional($variant->product)->name }}"
                                             style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <i class="fas fa-tshirt" style="color:#64748B;"></i>
                                    @endif
                                </div>

                                <div>
                                    <p class="table-main-text">{{ optional($variant->product)->name ?? '-' }}</p>
                                    <p class="table-sub-text">Product ID: {{ $variant->product_id ?: '-' }}</p>
                                </div>
                            </div>
                        </td>

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
                            <p class="table-main-text">
                                {{ $variant->price ? '₹' . number_format($variant->price, 2) : '-' }}
                            </p>

                            @if($variant->discount_price)
                                <p class="table-sub-text">
                                    Sale: ₹{{ number_format($variant->discount_price, 2) }}
                                </p>
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
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-dot status-success"></span>
                                    <span style="font-size:12.5px; color:#166534;">Active</span>
                                </div>
                            @else
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-dot status-warning"></span>
                                    <span style="font-size:12.5px; color:#92400E;">Inactive</span>
                                </div>
                            @endif
                        </td>

                        <td>
                            <div class="action-row">
                                @can('product_variant_show')
                                    <a href="{{ route('admin.product-variants.show', $variant->id) }}" class="btn-outline">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                @endcan

                                @can('product_variant_edit')
                                    <a href="{{ route('admin.product-variants.edit', $variant->id) }}" class="btn-outline btn-outline-edit">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </a>
                                @endcan

                                @can('product_variant_delete')
                                    <form action="{{ route('admin.product-variants.destroy', $variant->id) }}"
                                          method="POST"
                                          style="display:inline;"
                                          onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
                                        @method('DELETE')
                                        @csrf

                                        <button type="submit" class="btn-outline btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(function () {
    initAdminDataTable('.datatable-ProductVariant', {
        canDelete: @can('product_variant_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.product-variants.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search variants...',
        infoText: 'Showing _START_–_END_ of _TOTAL_ variants'
    });
});
</script>
@endsection