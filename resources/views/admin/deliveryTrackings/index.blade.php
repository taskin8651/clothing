@extends('layouts.admin')

@section('page-title', 'Delivery Trackings')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Delivery Trackings</h2>
        <p class="admin-page-subtitle">Track delivery assignment, pickup, COD and final delivery status</p>
    </div>

    @can('delivery_tracking_create')
        <a href="{{ route('admin.delivery-trackings.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Add Tracking
        </a>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card"><p class="stat-label">Total Trackings</p><p class="stat-value">{{ $deliveryTrackings->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">Assigned</p><p class="stat-value">{{ $deliveryTrackings->where('status', 'assigned')->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">Out For Delivery</p><p class="stat-value">{{ $deliveryTrackings->where('status', 'out_for_delivery')->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">Delivered</p><p class="stat-value">{{ $deliveryTrackings->where('status', 'delivered')->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">COD Pending</p><p class="stat-value">{{ $deliveryTrackings->filter(fn($tracking) => $tracking->cod_amount > 0 && ! $tracking->cod_collected)->count() }}</p></div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Delivery Trackings</p>
        <span class="page-card-note"><i class="fas fa-info-circle"></i> COD collection syncs the related order payment</span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-DeliveryTracking">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Tracking Number</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Shop</th>
                    <th>Delivery Boy</th>
                    <th>Status</th>
                    <th>COD</th>
                    <th>Pincode</th>
                    <th>Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveryTrackings as $deliveryTracking)
                    <tr data-entry-id="{{ $deliveryTracking->id }}">
                        <td></td>
                        <td><span class="id-text">#{{ $deliveryTracking->id }}</span></td>
                        <td><span class="code-pill">{{ $deliveryTracking->tracking_number }}</span></td>
                        <td>{{ optional($deliveryTracking->order)->order_number ?: '-' }}</td>
                        <td>
                            <p class="table-main-text">{{ optional($deliveryTracking->customer)->name ?: optional($deliveryTracking->order)->customer_name ?: '-' }}</p>
                            <p class="table-sub-text">{{ optional($deliveryTracking->customer)->mobile ?: optional($deliveryTracking->order)->customer_mobile ?: '-' }}</p>
                        </td>
                        <td>{{ optional($deliveryTracking->shop)->shop_name ?: '-' }}</td>
                        <td>{{ optional($deliveryTracking->deliveryBoy)->name ?: '-' }}</td>
                        <td><span class="status-pill {{ in_array($deliveryTracking->status, ['delivered', 'assigned', 'picked_up', 'out_for_delivery']) ? 'success' : 'warning' }}">{{ \App\Models\DeliveryTracking::STATUSES[$deliveryTracking->status] ?? $deliveryTracking->status }}</span></td>
                        <td>
                            @if($deliveryTracking->cod_amount > 0)
                                <span class="status-pill {{ $deliveryTracking->cod_collected ? 'success' : 'warning' }}">Rs. {{ number_format($deliveryTracking->cod_amount, 2) }} / {{ $deliveryTracking->cod_collected ? 'Paid' : 'Pending' }}</span>
                            @else
                                <span class="status-pill success">No COD</span>
                            @endif
                        </td>
                        <td><span class="code-pill">{{ $deliveryTracking->pincode ?: '-' }}</span></td>
                        <td>{{ optional($deliveryTracking->created_at)->format('d M Y') }}</td>
                        <td>
                            <div class="action-row">
                                @can('delivery_tracking_show')
                                    <a href="{{ route('admin.delivery-trackings.show', $deliveryTracking->id) }}" class="btn-outline"><i class="fas fa-eye"></i> View</a>
                                @endcan
                                @can('delivery_tracking_edit')
                                    <a href="{{ route('admin.delivery-trackings.edit', $deliveryTracking->id) }}" class="btn-outline btn-outline-edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                @endcan
                                @can('delivery_tracking_delete')
                                    <form action="{{ route('admin.delivery-trackings.destroy', $deliveryTracking->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn-outline btn-outline-danger"><i class="fas fa-trash-alt"></i> Delete</button>
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
    initAdminDataTable('.datatable-DeliveryTracking', {
        canDelete: @can('delivery_tracking_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.delivery-trackings.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search trackings...',
        infoText: 'Showing _START_-_END_ of _TOTAL_ trackings'
    });
});
</script>
@endsection
