@extends('layouts.admin')

@section('page-title', 'Edit Homepage Section')

@section('content')
<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.homepage-sections.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Edit Homepage Section</h2>
        <p class="admin-page-subtitle">{{ $homepageSection->title }}</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.homepage-sections.update', $homepageSection) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    @include('admin.homepageSections.partials.form', ['section' => $homepageSection])
</form>
@endsection
