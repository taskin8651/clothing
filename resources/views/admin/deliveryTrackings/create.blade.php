@extends('layouts.admin')

@section('page-title', 'Add Delivery Tracking')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.delivery-trackings.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Add Delivery Tracking</h2>
        <p class="admin-page-subtitle">Create tracking for pickup, delivery and COD collection</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.delivery-trackings.store') }}">
    @csrf
    <div class="admin-form-grid">
        @include('admin.deliveryTrackings.partials.form', ['deliveryTracking' => null])
    </div>
    <div class="form-actions">
        <button type="submit" class="btn-primary"><i class="fas fa-check"></i> Save Tracking</button>
        <a href="{{ route('admin.delivery-trackings.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>

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
