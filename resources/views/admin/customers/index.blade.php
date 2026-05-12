@extends('layouts.admin')

@section('page-title', 'Customers')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Customers</h2>
        <p class="admin-page-subtitle">
            Manage registered customers, mobile numbers, email and delivery addresses
        </p>
    </div>

    @can('customer_create')
        <a href="{{ route('admin.customers.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Add Customer
        </a>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card">
        <p class="stat-label">Total Customers</p>
        <p class="stat-value">{{ $customers->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Active</p>
        <p class="stat-value">{{ $customers->where('status', 1)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Inactive</p>
        <p class="stat-value">{{ $customers->where('status', 0)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Addresses</p>
        <p class="stat-value">{{ $customers->sum(fn($customer) => $customer->addresses->count()) }}</p>
    </div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Customers</p>

        <span class="page-card-note">
            <i class="fas fa-info-circle"></i>
            Customer address nearest shop delivery me use hoga
        </span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-Customer">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Default Address</th>
                    <th>Addresses</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($customers as $customer)
                    @php
                        $colors = ['#4F46E5','#0EA5E9','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6'];
                        $color  = $colors[$customer->id % count($colors)];
                    @endphp

                    <tr data-entry-id="{{ $customer->id }}">
                        <td></td>

                        <td>
                            <span class="id-text">#{{ $customer->id }}</span>
                        </td>

                        <td>
                            <div class="inline-flex-center">
                                <div class="avatar-circle" style="background: {{ $color }};">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>

                                <div>
                                    <p class="table-main-text">{{ $customer->name }}</p>
                                    <p class="table-sub-text">Customer</p>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span style="font-size:12.5px; color:#475569;">
                                {{ $customer->mobile ?: '-' }}
                            </span>
                        </td>

                        <td>
                            <span style="font-size:12.5px; color:#475569;">
                                {{ $customer->display_email ?: '-' }}
                            </span>
                        </td>

                        <td>
                            @if($customer->defaultAddress)
                                <p class="table-main-text">{{ $customer->defaultAddress->city ?: '-' }}</p>
                                <p class="table-sub-text">
                                    {{ $customer->defaultAddress->area ?: '-' }}
                                    {{ $customer->defaultAddress->pincode ? ' - ' . $customer->defaultAddress->pincode : '' }}
                                </p>
                            @else
                                <span style="font-size:12px; color:#94A3B8;">No default address</span>
                            @endif
                        </td>

                        <td>
                            <span class="status-pill success">{{ $customer->addresses->count() }}</span>
                        </td>

                        <td>
                            @if($customer->status)
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
                                @can('customer_show')
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn-outline">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                @endcan

                                @can('customer_edit')
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn-outline btn-outline-edit">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </a>
                                @endcan

                                @can('customer_delete')
                                    <form action="{{ route('admin.customers.destroy', $customer->id) }}"
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
    initAdminDataTable('.datatable-Customer', {
        canDelete: @can('customer_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.customers.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search customers...',
        infoText: 'Showing _START_–_END_ of _TOTAL_ customers'
    });
});
</script>
@endsection
