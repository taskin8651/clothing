@extends('layouts.admin')
@section('page-title','Add Receipt')
@section('content')
<div class="admin-page-head"><div><a href="{{ route('admin.receipts.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a><h2 class="admin-page-title">Add Receipt</h2><p class="admin-page-subtitle">Create receipt for invoice or payment</p></div></div>
<form method="POST" action="{{ route('admin.receipts.store') }}">@csrf<div class="admin-form-grid">@include('admin.receipts.partials.form',['receipt'=>null])</div><div class="form-actions"><button class="btn-primary"><i class="fas fa-check"></i> Save Receipt</button><a href="{{ route('admin.receipts.index') }}" class="btn-ghost">Cancel</a></div></form>
@endsection
