@extends('layouts.admin')

@section('page-title', 'Products')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Products</h2>
        <p class="admin-page-subtitle">
            Manage clothing products, shops, categories, variants, stock and return rules
        </p>
    </div>

    @can('product_create')
        <a href="{{ route('admin.products.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Add Product
        </a>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card">
        <p class="stat-label">Total Products</p>
        <p class="stat-value">{{ $products->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Active</p>
        <p class="stat-value">{{ $products->where('status', 1)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Featured</p>
        <p class="stat-value">{{ $products->where('is_featured', 1)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Variants</p>
        <p class="stat-value">{{ $products->sum(fn($product) => $product->variants->count()) }}</p>
    </div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Products</p>

        <span class="page-card-note">
            <i class="fas fa-info-circle"></i>
            Product image, variant stock, shop and category details
        </span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-Product">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Shop</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Variants</th>
                    <th>Stock</th>
                    <th>Try Cloth</th>
                    <th>Return</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($products as $product)
                    @php
                        $variantStock = $product->variants->sum('stock_quantity');
                        $totalStock = $product->variants->count() ? $variantStock : $product->stock_quantity;
                    @endphp

                    <tr data-entry-id="{{ $product->id }}">
                        <td></td>

                        <td>
                            <span class="id-text">#{{ $product->id }}</span>
                        </td>

                        <td>
                            <div class="inline-flex-center">
                                <div class="avatar-circle" style="background:#F8FAFC; border:1px solid #E2E8F0; overflow:hidden;">
                                    @if($product->main_image)
                                        <img src="{{ $product->main_image['url'] }}"
                                             alt="{{ $product->name }}"
                                             style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <i class="fas fa-tshirt" style="color:#64748B;"></i>
                                    @endif
                                </div>

                                <div>
                                    <p class="table-main-text">{{ $product->name }}</p>
                                    <p class="table-sub-text">
                                        SKU: {{ $product->sku ?: '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span style="font-size:12.5px; color:#475569;">
                                {{ optional($product->shop)->shop_name ?? '-' }}
                            </span>
                        </td>

                        <td>
                            <span class="role-tag">
                                {{ optional($product->category)->name ?? '-' }}
                            </span>
                        </td>

                        <td>
                            <div>
                                <p class="table-main-text">
                                    ₹{{ number_format($product->price, 2) }}
                                </p>

                                @if($product->discount_price)
                                    <p class="table-sub-text">
                                        Sale: ₹{{ number_format($product->discount_price, 2) }}
                                    </p>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if($product->variants->count())
                                <span class="status-pill success">
                                    {{ $product->variants->count() }}
                                </span>
                            @else
                                <span class="status-pill warning">No</span>
                            @endif
                        </td>

                        <td>
                            @if($totalStock <= 0)
                                <span class="status-pill warning">Out</span>
                            @elseif($totalStock <= 5)
                                <span class="status-pill warning">{{ $totalStock }} left</span>
                            @else
                                <span class="status-pill success">{{ $totalStock }}</span>
                            @endif
                        </td>

                        <td>
                            @if($product->try_cloth_available)
                                <span class="status-pill success">Yes</span>
                            @else
                                <span class="status-pill warning">No</span>
                            @endif
                        </td>

                        <td>
                            @if($product->return_available)
                                <span class="status-pill success">Allowed</span>
                            @else
                                <span class="status-pill warning">No Return</span>
                            @endif
                        </td>

                        <td>
                            @if($product->status)
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
                                @can('product_show')
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn-outline">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                @endcan

                                @can('product_edit')
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-outline btn-outline-edit">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </a>
                                @endcan

                                @can('product_delete')
                                    <form action="{{ route('admin.products.destroy', $product->id) }}"
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
    initAdminDataTable('.datatable-Product', {
        canDelete: @can('product_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.products.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search products...',
        infoText: 'Showing _START_–_END_ of _TOTAL_ products'
    });
});
</script>
@endsection