@extends('layouts.admin')

@section('page-title', 'Orders')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Orders</h2>
        <p class="admin-page-subtitle">Manage clothing marketplace orders, payments and delivery assignment</p>
    </div>

    @can('order_create')
        <a href="{{ route('admin.orders.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Add Order
        </a>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card">
        <p class="stat-label">Total Orders</p>
        <p class="stat-value">{{ $orders->count() }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Pending</p>
        <p class="stat-value">{{ $orders->where('order_status', 'pending')->count() }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Delivered</p>
        <p class="stat-value">{{ $orders->where('order_status', 'delivered')->count() }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">COD Orders</p>
        <p class="stat-value">{{ $orders->where('payment_method', 'cod')->count() }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Online Orders</p>
        <p class="stat-value">{{ $orders->where('payment_method', 'online')->count() }}</p>
    </div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Orders</p>
        <span class="page-card-note">
            <i class="fas fa-info-circle"></i>
            Try Cloth orders are non-returnable
        </span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-Order">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Shop</th>
                    <th>Delivery Boy</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Try Cloth</th>
                    <th>Return</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($orders as $order)
                    <tr data-entry-id="{{ $order->id }}">
                        <td></td>
                        <td><span class="id-text">#{{ $order->id }}</span></td>
                        <td><span class="code-pill">{{ $order->order_number }}</span></td>
                        <td>
                            <p class="table-main-text">{{ $order->customer_name ?: optional($order->customer)->name ?: '-' }}</p>
                            <p class="table-sub-text">{{ $order->customer_mobile ?: optional($order->customer)->mobile ?: '-' }}</p>
                        </td>
                        <td>
                            <p class="table-main-text">{{ optional($order->shop)->shop_name ?: '-' }}</p>
                            <p class="table-sub-text">{{ optional($order->shop)->area ?: '-' }}</p>
                        </td>
                        <td>
                            <p class="table-main-text">{{ optional($order->deliveryBoy)->name ?: '-' }}</p>
                            <p class="table-sub-text">{{ optional($order->deliveryBoy)->mobile ?: '-' }}</p>
                        </td>
                        <td>
                            <p class="table-main-text">{{ strtoupper($order->payment_method ?: '-') }}</p>
                            <p class="table-sub-text">{{ ucfirst($order->payment_status) }}</p>
                        </td>
                        <td><span class="status-pill success">{{ \App\Models\Order::ORDER_STATUSES[$order->order_status] ?? $order->order_status }}</span></td>
                        <td><span class="status-pill {{ $order->try_cloth_selected ? 'warning' : 'success' }}">{{ $order->try_cloth_selected ? 'Yes' : 'No' }}</span></td>
                        <td><span class="status-pill {{ $order->return_eligible ? 'success' : 'warning' }}">{{ $order->return_eligible ? 'Allowed' : 'No' }}</span></td>
                        <td><span class="code-pill">₹{{ number_format($order->total_amount, 2) }}</span></td>
                        <td><span style="font-size:12.5px;color:#475569;">{{ optional($order->created_at)->format('d M Y') }}</span></td>
                        <td>
                            <div class="action-row">
                                @can('order_show')
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-outline">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                @endcan
                                @can('order_edit')
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn-outline btn-outline-edit">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </a>
                                @endcan
                                @can('order_delete')
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
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
    initAdminDataTable('.datatable-Order', {
        canDelete: @can('order_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.orders.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search orders...',
        infoText: 'Showing _START_-_END_ of _TOTAL_ orders'
    });
});
</script>
@endsection

