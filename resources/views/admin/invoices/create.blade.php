@extends('layouts.admin')
@section('page-title', 'Add Invoice')
@section('content')
<div class="admin-page-head"><div><a href="{{ route('admin.invoices.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a><h2 class="admin-page-title">Add Invoice</h2><p class="admin-page-subtitle">Create invoice snapshot manually</p></div></div>
<form method="POST" action="{{ route('admin.invoices.store') }}">@csrf<div class="admin-form-grid">@include('admin.invoices.partials.form', ['invoice' => null])</div><div class="form-actions"><button class="btn-primary"><i class="fas fa-check"></i> Save Invoice</button><a href="{{ route('admin.invoices.index') }}" class="btn-ghost">Cancel</a></div></form>
@endsection
