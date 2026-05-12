@extends('layouts.admin')

@section('page-title', 'Shops')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Shops</h2>
        <p class="admin-page-subtitle">Manage local clothing shops and delivery coverage details</p>
    </div>

    @can('shop_create')
        <a href="{{ route('admin.shops.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Add Shop
        </a>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card">
        <p class="stat-label">Total Shops</p>
        <p class="stat-value">{{ $shops->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Active</p>
        <p class="stat-value">{{ $shops->where('status', 1)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Inactive</p>
        <p class="stat-value">{{ $shops->where('status', 0)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Products</p>
        <p class="stat-value">{{ $shops->sum(fn($shop) => $shop->products->count()) }}</p>
    </div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Shops</p>

        <span class="page-card-note">
            <i class="fas fa-info-circle"></i>
            Nearest shop assignment ke liye pincode/location important hai
        </span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-Shop">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Shop</th>
                    <th>Owner</th>
                    <th>Mobile</th>
                    <th>City / Area</th>
                    <th>Pincode</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($shops as $shop)
                    <tr data-entry-id="{{ $shop->id }}">
                        <td></td>

                        <td>
                            <span class="id-text">#{{ $shop->id }}</span>
                        </td>

                        <td>
                            <div class="inline-flex-center">
                                <div class="avatar-circle" style="background:#F8FAFC; border:1px solid #E2E8F0; overflow:hidden;">
                                    @if($shop->shop_image)
                                        <img src="{{ $shop->shop_image['url'] }}"
                                             alt="{{ $shop->shop_name }}"
                                             style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <i class="fas fa-store" style="color:#64748B;"></i>
                                    @endif
                                </div>

                                <div>
                                    <p class="table-main-text">{{ $shop->shop_name }}</p>
                                    <p class="table-sub-text">{{ $shop->email ?: '-' }}</p>
                                </div>
                            </div>
                        </td>

                        <td>{{ $shop->owner_name ?: '-' }}</td>
                        <td>{{ $shop->mobile ?: '-' }}</td>

                        <td>
                            <p class="table-main-text">{{ $shop->city ?: '-' }}</p>
                            <p class="table-sub-text">{{ $shop->area ?: '-' }}</p>
                        </td>

                        <td>
                            <span class="role-tag">{{ $shop->pincode ?: '-' }}</span>
                        </td>

                        <td>
                            <span class="status-pill success">{{ $shop->products->count() }}</span>
                        </td>

                        <td>
                            @if($shop->status)
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
                                @can('shop_show')
                                    <a href="{{ route('admin.shops.show', $shop->id) }}" class="btn-outline">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                @endcan

                                @can('shop_edit')
                                    <a href="{{ route('admin.shops.edit', $shop->id) }}" class="btn-outline btn-outline-edit">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </a>
                                @endcan

                                @can('shop_delete')
                                    <form action="{{ route('admin.shops.destroy', $shop->id) }}"
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
    initAdminDataTable('.datatable-Shop', {
        canDelete: @can('shop_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.shops.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search shops...',
        infoText: 'Showing _START_–_END_ of _TOTAL_ shops'
    });
});
</script>
@endsection