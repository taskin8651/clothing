@extends('layouts.admin')

@section('page-title', 'Edit Delivery Tracking')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.delivery-trackings.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Edit Delivery Tracking</h2>
        <p class="admin-page-subtitle">Update assignment, address, status, COD and notes</p>
    </div>
    <div class="identity-card">
        <div class="identity-avatar" style="background:#4F46E5;"><i class="fas fa-route"></i></div>
        <div>
            <p class="identity-title">{{ $deliveryTracking->tracking_number }}</p>
            <p class="identity-subtitle">Tracking ID #{{ $deliveryTracking->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.delivery-trackings.update', $deliveryTracking->id) }}">
    @method('PUT')
    @csrf
    <div class="admin-form-grid">
        @include('admin.deliveryTrackings.partials.form', ['deliveryTracking' => $deliveryTracking])
    </div>
    <div class="form-actions-between">
        <div class="form-actions-left">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Tracking</button>
            <a href="{{ route('admin.delivery-trackings.index') }}" class="btn-ghost">Cancel</a>
        </div>
        @can('delivery_tracking_delete')
            <button type="submit" form="delete-delivery-tracking-form" class="btn-danger"><i class="fas fa-trash-alt"></i> Delete Delivery Tracking</button>
        @endcan
    </div>
</form>

@can('delivery_tracking_delete')
    <form id="delete-delivery-tracking-form" action="{{ route('admin.delivery-trackings.destroy', $deliveryTracking->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
        @method('DELETE')
        @csrf
    </form>
@endcan

@endsection

@section('scripts')
@parent
<script>
document.querySelectorAll('.role-checkbox-item input[type="checkbox"]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        const label = this.closest('.role-checkbox-item');
        this.checked ? label.classList.add('checked') : label.classList.remove('checked');
    });
});
</script>
@endsection
