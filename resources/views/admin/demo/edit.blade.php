@extends('layouts.admin')

@section('page-title', 'Edit Category')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.categories.index') }}" class="admin-back-link">
            ← {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Edit Category</h2>

        <p class="admin-page-subtitle">Update clothing category details</p>
    </div>

    <div class="identity-card">
        <div class="identity-avatar" style="background:#F8FAFC; border:1px solid #E2E8F0; overflow:hidden;">
            @if($category->category_image)
                <img src="{{ $category->category_image['url'] }}" alt="{{ $category->name }}" style="width:100%; height:100%; object-fit:cover;">
            @else
                <i class="fas fa-layer-group" style="color:#64748B;"></i>
            @endif
        </div>

        <div>
            <p class="identity-title">{{ $category->name }}</p>
            <p class="identity-subtitle">ID #{{ $category->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.categories.update', $category->id) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf

    <div class="admin-form-grid">

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-layer-group"></i>
                </div>

                <div>
                    <p class="form-card-title">Category Information</p>
                    <p class="form-card-subtitle">Update category details</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="name">Category Name <span class="req">*</span></label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-tag icon"></i>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                               class="field-input {{ $errors->has('name') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('name'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="slug">Slug</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-link icon"></i>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                               class="field-input {{ $errors->has('slug') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('slug'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('slug') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="parent_id">Parent Category</label>

                    <select name="parent_id" id="parent_id" class="field-input {{ $errors->has('parent_id') ? 'error' : '' }}">
                        @foreach($parents as $id => $entry)
                            <option value="{{ $id }}" {{ old('parent_id', $category->parent_id) == $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>

                    @if($errors->has('parent_id'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('parent_id') }}</p>
                    @endif
                </div>

                @if($category->category_image)
                    <div class="field-group">
                        <label class="field-label">Current Category Image</label>

                        <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                            <img src="{{ $category->category_image['url'] }}"
                                 alt="{{ $category->name }}"
                                 style="width:150px;height:125px;object-fit:cover;border-radius:16px;border:1px solid #E2E8F0;">

                            <label class="role-checkbox-item">
                                <input type="checkbox" name="remove_category_image" value="1" class="role-checkbox">
                                <div class="check-icon"></div>
                                <span class="checkbox-text">Remove Image</span>
                            </label>
                        </div>
                    </div>
                @endif

                <div class="field-group">
                    <label class="field-label" for="category_image">
                        {{ $category->category_image ? 'Replace Category Image' : 'Category Image' }}
                    </label>

                    <input type="file" name="category_image" id="category_image" accept="image/*"
                           class="field-input {{ $errors->has('category_image') ? 'error' : '' }}"
                           onchange="previewSingleImage(this, 'categoryImagePreview')">

                    @if($errors->has('category_image'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('category_image') }}</p>
                    @endif

                    <div id="categoryImagePreview" style="margin-top:12px;"></div>
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>

                <div>
                    <p class="form-card-title">Category Settings</p>
                    <p class="form-card-subtitle">Sorting and visibility</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="sort_order">Sort Order</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-sort-numeric-up icon"></i>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="field-input">
                    </div>
                </div>

                <div class="checkbox-grid">
                    <label class="role-checkbox-item {{ old('status', $category->status) ? 'checked' : '' }}">
                        <input type="checkbox" name="status" value="1" class="role-checkbox" {{ old('status', $category->status) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Active Status</span>
                    </label>
                </div>

                <div class="form-info-box">
                    <p>
                        <i class="fas fa-info-circle"></i>
                        Category inactive hone par product form me visible nahi hogi.
                    </p>
                </div>

            </div>
        </div>

    </div>

    <div class="form-actions-between">
        <div class="form-actions-left">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Save Category
            </button>

            <a href="{{ route('admin.categories.index') }}" class="btn-ghost">Cancel</a>
        </div>

        @can('category_delete')
            <button type="submit" form="delete-category-form" class="btn-danger">
                <i class="fas fa-trash-alt"></i>
                Delete Category
            </button>
        @endcan
    </div>
</form>

@can('category_delete')
    <form id="delete-category-form"
          action="{{ route('admin.categories.destroy', $category->id) }}"
          method="POST"
          onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
        @method('DELETE')
        @csrf
    </form>
@endcan

@endsection

@section('scripts')
@parent
<script>
function previewSingleImage(input, targetId) {
    const target = document.getElementById(targetId);
    target.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            target.innerHTML = `
                <img src="${e.target.result}"
                     style="width:150px;height:125px;object-fit:cover;border-radius:16px;border:1px solid #E2E8F0;">
            `;
        };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection