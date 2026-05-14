@extends('layouts.admin')

@section('page-title', 'Add Delivery Zone')

@section('content')
<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.delivery-zones.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Add Delivery Zone</h2>
        <p class="admin-page-subtitle">Create pincode based quick-commerce availability</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.delivery-zones.store') }}">
    @csrf
    @include('admin.deliveryZones.partials.form', ['zone' => null])
</form>
@endsection
