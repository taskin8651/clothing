@extends('layouts.admin')

@section('page-title', 'Edit Delivery Zone')

@section('content')
<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.delivery-zones.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Edit Delivery Zone</h2>
        <p class="admin-page-subtitle">{{ $deliveryZone->city }} - {{ $deliveryZone->pincode }}</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.delivery-zones.update', $deliveryZone) }}">
    @method('PUT')
    @csrf
    @include('admin.deliveryZones.partials.form', ['zone' => $deliveryZone])
</form>
@endsection
