@extends('layouts.admin')

@section('page-title', 'Delivery Boys')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Delivery Boys</h2>
        <p class="admin-page-subtitle">Manage delivery staff, vehicles and ID proof documents</p>
    </div>

    @can('delivery_boy_create')
        <a href="{{ route('admin.delivery-boys.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Add Delivery Boy
        </a>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card">
        <p class="stat-label">Total Delivery Boys</p>
        <p class="stat-value">{{ $deliveryBoys->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Active</p>
        <p class="stat-value">{{ $deliveryBoys->where('status', 1)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Inactive</p>
        <p class="stat-value">{{ $deliveryBoys->where('status', 0)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">With Vehicle</p>
        <p class="stat-value">{{ $deliveryBoys->filter(fn($deliveryBoy) => $deliveryBoy->vehicle_type || $deliveryBoy->vehicle_number)->count() }}</p>
    </div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Delivery Boys</p>

        <span class="page-card-note">
            <i class="fas fa-info-circle"></i>
            Delivery boys can later be assigned orders
        </span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-DeliveryBoy">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Delivery Boy</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>City / Area</th>
                    <th>Vehicle</th>
                    <th>ID Proof Type</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($deliveryBoys as $deliveryBoy)
                    @php
                        $colors = ['#4F46E5','#0EA5E9','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6'];
                        $color = $colors[$deliveryBoy->id % count($colors)];
                    @endphp

                    <tr data-entry-id="{{ $deliveryBoy->id }}">
                        <td></td>

                        <td><span class="id-text">#{{ $deliveryBoy->id }}</span></td>

                        <td>
                            <div class="inline-flex-center">
                                @if($deliveryBoy->profile_image)
                                    <img src="{{ $deliveryBoy->profile_image['url'] }}" alt="{{ $deliveryBoy->name }}" class="avatar-circle" style="object-fit:cover;">
                                @else
                                    <div class="avatar-circle" style="background: {{ $color }};">
                                        {{ strtoupper(substr($deliveryBoy->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>
                                    <p class="table-main-text">{{ $deliveryBoy->name }}</p>
                                    <p class="table-sub-text">Delivery</p>
                                </div>
                            </div>
                        </td>

                        <td><span style="font-size:12.5px; color:#475569;">{{ $deliveryBoy->mobile ?: '-' }}</span></td>
                        <td><span style="font-size:12.5px; color:#475569;">{{ $deliveryBoy->email ?: '-' }}</span></td>

                        <td>
                            <p class="table-main-text">{{ $deliveryBoy->city ?: '-' }}</p>
                            <p class="table-sub-text">{{ $deliveryBoy->area ?: '-' }}</p>
                        </td>

                        <td>
                            <p class="table-main-text">{{ $deliveryBoy->vehicle_type ?: '-' }}</p>
                            <p class="table-sub-text">{{ $deliveryBoy->vehicle_number ?: '-' }}</p>
                        </td>

                        <td><span class="code-pill">{{ $deliveryBoy->id_proof_type ?: '-' }}</span></td>

                        <td>
                            @if($deliveryBoy->status)
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
                                @can('delivery_boy_show')
                                    <a href="{{ route('admin.delivery-boys.show', $deliveryBoy->id) }}" class="btn-outline">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                @endcan

                                @can('delivery_boy_edit')
                                    <a href="{{ route('admin.delivery-boys.edit', $deliveryBoy->id) }}" class="btn-outline btn-outline-edit">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </a>
                                @endcan

                                @can('delivery_boy_delete')
                                    <form action="{{ route('admin.delivery-boys.destroy', $deliveryBoy->id) }}"
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
    initAdminDataTable('.datatable-DeliveryBoy', {
        canDelete: @can('delivery_boy_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.delivery-boys.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search delivery boys...',
        infoText: 'Showing _START_-_END_ of _TOTAL_ delivery boys'
    });
});
</script>
@endsection
