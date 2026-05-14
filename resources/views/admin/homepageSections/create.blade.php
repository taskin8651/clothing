@extends('layouts.admin')

@section('page-title', 'Add Homepage Section')

@section('content')
<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.homepage-sections.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Add Homepage Section</h2>
        <p class="admin-page-subtitle">Create banner, offer or collection block</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.homepage-sections.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.homepageSections.partials.form', ['section' => null])
</form>
@endsection
