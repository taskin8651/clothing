@extends('layouts.admin')

@section('page-title', 'Customer Addresses')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Customer Addresses</h2>
        <p class="admin-page-subtitle">
            Manage delivery addresses for customers and nearest shop assignment
        </p>
    </div>

    @can('customer_address_create')
        <a href="{{ route('admin.customer-addresses.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Add Address
        </a>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card">
        <p class="stat-label">Total Addresses</p>
        <p class="stat-value">{{ $customerAddresses->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Default</p>
        <p class="stat-value">{{ $customerAddresses->where('is_default', 1)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Active</p>
        <p class="stat-value">{{ $customerAddresses->where('status', 1)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">With Pincode</p>
        <p class="stat-value">{{ $customerAddresses->whereNotNull('pincode')->count() }}</p>
    </div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Customer Addresses</p>

        <span class="page-card-note">
            <i class="fas fa-info-circle"></i>
            Pincode/location nearest shop matching me use hoga
        </span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-CustomerAddress">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Receiver</th>
                    <th>Location</th>
                    <th>Pincode</th>
                    <th>Default</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($customerAddresses as $address)
                    <tr data-entry-id="{{ $address->id }}">
                        <td></td>

                        <td>
                            <span class="id-text">#{{ $address->id }}</span>
                        </td>

                        <td>
                            <p class="table-main-text">{{ optional($address->customer)->name ?? '-' }}</p>
                            <p class="table-sub-text">{{ optional($address->customer)->mobile ?? '-' }}</p>
                        </td>

                        <td>
                            <p class="table-main-text">{{ $address->name ?: optional($address->customer)->name }}</p>
                            <p class="table-sub-text">{{ $address->mobile ?: optional($address->customer)->mobile }}</p>
                        </td>

                        <td>
                            <p class="table-main-text">{{ $address->city ?: '-' }}</p>
                            <p class="table-sub-text">{{ $address->area ?: '-' }}</p>
                        </td>

                        <td>
                            <span class="code-pill">{{ $address->pincode ?: '-' }}</span>
                        </td>

                        <td>
                            @if($address->is_default)
                                <span class="status-pill success">Default</span>
                            @else
                                <span class="status-pill warning">No</span>
                            @endif
                        </td>

                        <td>
                            @if($address->status)
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

                                @can('customer_address_delete')
                                    <form action="{{ route('admin.customer-addresses.destroy', $address->id) }}"
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
    initAdminDataTable('.datatable-CustomerAddress', {
        canDelete: @can('customer_address_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.customer-addresses.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search addresses...',
        infoText: 'Showing _START_–_END_ of _TOTAL_ addresses'
    });
});
</script>
@endsection