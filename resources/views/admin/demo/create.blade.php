@extends('layouts.admin')

@section('page-title', 'Add Category')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.categories.index') }}" class="admin-back-link">
            ← {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Add Category</h2>

        <p class="admin-page-subtitle">Create clothing category or sub category</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="admin-form-grid">

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-layer-group"></i>
                </div>

                <div>
                    <p class="form-card-title">Category Information</p>
                    <p class="form-card-subtitle">Basic category details</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="name">Category Name <span class="req">*</span></label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-tag icon"></i>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               placeholder="Example: Men Wear"
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
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                               placeholder="men-wear"
                               class="field-input {{ $errors->has('slug') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('slug'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('slug') }}</p>
                    @else
                        <p class="field-hint">Blank rakhne par slug auto generate hoga.</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="parent_id">Parent Category</label>

                    <select name="parent_id" id="parent_id" class="field-input {{ $errors->has('parent_id') ? 'error' : '' }}">
                        @foreach($parents as $id => $entry)
                            <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>

                    @if($errors->has('parent_id'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('parent_id') }}</p>
                    @else
                        <p class="field-hint">Sub category banani ho to parent select karo.</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="category_image">Category Image</label>

                    <input type="file" name="category_image" id="category_image" accept="image/*"
                           class="field-input {{ $errors->has('category_image') ? 'error' : '' }}"
                           onchange="previewSingleImage(this, 'categoryImagePreview')">

                    @if($errors->has('category_image'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('category_image') }}</p>
                    @else
                        <p class="field-hint">JPG, PNG, WEBP. Max 4MB.</p>
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
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" class="field-input">
                    </div>
                </div>

                <div class="checkbox-grid">
                    <label class="role-checkbox-item checked">
                        <input type="checkbox" name="status" value="1" class="role-checkbox" {{ old('status', 1) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Active Status</span>
                    </label>
                </div>

                <div class="form-info-box">
                    <p>
                        <i class="fas fa-info-circle"></i>
                        Active categories hi product create/edit me visible hongi.
                    </p>
                </div>

            </div>
        </div>

    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="fas fa-check"></i>
            Save Category
        </button>

        <a href="{{ route('admin.categories.index') }}" class="btn-ghost">Cancel</a>
    </div>

</form>

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