@extends('layouts.admin')

@section('page-title', 'Delivery Zones')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Delivery Zones</h2>
        <p class="admin-page-subtitle">Manage serviceable pincodes, delivery speed and home trial rules</p>
    </div>

    @can('delivery_zone_create')
        <a href="{{ route('admin.delivery-zones.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Add Zone
        </a>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card"><p class="stat-label">Total Zones</p><p class="stat-value">{{ $deliveryZones->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">Active</p><p class="stat-value">{{ $deliveryZones->where('status', 1)->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">Try First</p><p class="stat-value">{{ $deliveryZones->where('try_first_enabled', 1)->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">COD Enabled</p><p class="stat-value">{{ $deliveryZones->where('cod_enabled', 1)->count() }}</p></div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">Serviceable Areas</p>
        <span class="page-card-note"><i class="fas fa-info-circle"></i> Pincode ke basis par quick delivery availability manage hogi</span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-DeliveryZone">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Location</th>
                    <th>Shop</th>
                    <th>Delivery</th>
                    <th>Trial</th>
                    <th>Charge</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveryZones as $zone)
                    <tr data-entry-id="{{ $zone->id }}">
                        <td></td>
                        <td><span class="id-text">#{{ $zone->id }}</span></td>
                        <td>
                            <p class="table-main-text">{{ $zone->area ?: $zone->city }}</p>
                            <p class="table-sub-text">{{ $zone->city }} - {{ $zone->pincode }}</p>
                        </td>
                        <td><span class="role-tag">{{ optional($zone->shop)->shop_name ?? 'All Shops' }}</span></td>
                        <td><span class="code-pill">{{ $zone->min_delivery_minutes }}-{{ $zone->max_delivery_minutes }} min</span></td>
                        <td>
                            @if($zone->try_first_enabled)
                                <span class="status-pill success">{{ $zone->trial_wait_minutes }} min wait</span>
                            @else
                                <span class="status-pill warning">Disabled</span>
                            @endif
                        </td>
                        <td><span class="code-pill">Rs {{ $zone->delivery_charge }}</span></td>
                        <td>
                            @if($zone->status)
                                <span class="status-pill success">Active</span>
                            @else
                                <span class="status-pill warning">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-row">
                                @can('delivery_zone_show')
                                    <a href="{{ route('admin.delivery-zones.show', $zone) }}" class="btn-outline"><i class="fas fa-eye"></i> View</a>
                                @endcan
                                @can('delivery_zone_edit')
                                    <a href="{{ route('admin.delivery-zones.edit', $zone) }}" class="btn-outline btn-outline-edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                @endcan
                                @can('delivery_zone_delete')
                                    <form action="{{ route('admin.delivery-zones.destroy', $zone) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
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
    initAdminDataTable('.datatable-DeliveryZone', {
        canDelete: @can('delivery_zone_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.delivery-zones.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search delivery zones...',
        infoText: 'Showing _START_-_END_ of _TOTAL_ zones'
    });
});
</script>
@endsection
