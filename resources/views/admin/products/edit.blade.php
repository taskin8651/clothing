@extends('layouts.admin')

@section('page-title', 'Edit Product')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.products.index') }}" class="admin-back-link">
            ← {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Edit Product</h2>

        <p class="admin-page-subtitle">
            Update product details, images, stock and return settings
        </p>
    </div>

    <div class="identity-card">
        <div class="identity-avatar" style="background:#F8FAFC; border:1px solid #E2E8F0; overflow:hidden;">
            @if($product->main_image)
                <img src="{{ $product->main_image['url'] }}"
                     alt="{{ $product->name }}"
                     style="width:100%; height:100%; object-fit:cover;">
            @else
                <i class="fas fa-tshirt" style="color:#64748B;"></i>
            @endif
        </div>

        <div>
            <p class="identity-title">{{ $product->name }}</p>
            <p class="identity-subtitle">ID #{{ $product->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf

    <div class="admin-form-grid">

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-tshirt"></i>
                </div>

                <div>
                    <p class="form-card-title">Product Information</p>
                    <p class="form-card-subtitle">Update basic details</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="name">Product Name <span class="req">*</span></label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-tag icon"></i>

                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $product->name) }}"
                               required
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

                        <input type="text"
                               name="slug"
                               id="slug"
                               value="{{ old('slug', $product->slug) }}"
                               class="field-input {{ $errors->has('slug') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('slug'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('slug') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="sku">SKU</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-barcode icon"></i>

                        <input type="text"
                               name="sku"
                               id="sku"
                               value="{{ old('sku', $product->sku) }}"
                               class="field-input {{ $errors->has('sku') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('sku'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('sku') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="shop_id">Shop</label>

                    <select name="shop_id" id="shop_id" class="field-input {{ $errors->has('shop_id') ? 'error' : '' }}">
                        @foreach($shops as $id => $entry)
                            <option value="{{ $id }}" {{ old('shop_id', $product->shop_id) == $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>

                    @if($errors->has('shop_id'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('shop_id') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="category_id">Category</label>

                    <select name="category_id" id="category_id" class="field-input {{ $errors->has('category_id') ? 'error' : '' }}">
                        @foreach($categories as $id => $entry)
                            <option value="{{ $id }}" {{ old('category_id', $product->category_id) == $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>

                    @if($errors->has('category_id'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('category_id') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="short_description">Short Description</label>

                    <textarea name="short_description"
                              id="short_description"
                              rows="3"
                              class="field-input {{ $errors->has('short_description') ? 'error' : '' }}">{{ old('short_description', $product->short_description) }}</textarea>

                    @if($errors->has('short_description'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('short_description') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="description">Description</label>

                    <textarea name="description"
                              id="description"
                              rows="5"
                              class="field-input {{ $errors->has('description') ? 'error' : '' }}">{{ old('description', $product->description) }}</textarea>

                    @if($errors->has('description'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('description') }}</p>
                    @endif
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-rupee-sign"></i>
                </div>

                <div>
                    <p class="form-card-title">Price & Stock</p>
                    <p class="form-card-subtitle">Update pricing and inventory</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="price">Price <span class="req">*</span></label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-rupee-sign icon"></i>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="price"
                               id="price"
                               value="{{ old('price', $product->price) }}"
                               required
                               class="field-input {{ $errors->has('price') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('price'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('price') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="discount_price">Discount Price</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-percent icon"></i>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="discount_price"
                               id="discount_price"
                               value="{{ old('discount_price', $product->discount_price) }}"
                               class="field-input {{ $errors->has('discount_price') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('discount_price'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('discount_price') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="stock_quantity">Stock Quantity</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-boxes icon"></i>

                        <input type="number"
                               min="0"
                               name="stock_quantity"
                               id="stock_quantity"
                               value="{{ old('stock_quantity', $product->stock_quantity) }}"
                               class="field-input {{ $errors->has('stock_quantity') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('stock_quantity'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('stock_quantity') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="brand">Brand</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-certificate icon"></i>

                        <input type="text"
                               name="brand"
                               id="brand"
                               value="{{ old('brand', $product->brand) }}"
                               class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="fabric">Fabric</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-layer-group icon"></i>

                        <input type="text"
                               name="fabric"
                               id="fabric"
                               value="{{ old('fabric', $product->fabric) }}"
                               class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="sort_order">Sort Order</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-sort-numeric-up icon"></i>

                        <input type="number"
                               name="sort_order"
                               id="sort_order"
                               value="{{ old('sort_order', $product->sort_order) }}"
                               class="field-input">
                    </div>
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-image"></i>
                </div>

                <div>
                    <p class="form-card-title">Product Images</p>
                    <p class="form-card-subtitle">Replace main image or add/remove gallery</p>
                </div>
            </div>

            <div class="form-card-body">

                @if($product->main_image)
                    <div class="field-group">
                        <label class="field-label">Current Main Image</label>

                        <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                            <img src="{{ $product->main_image['url'] }}"
                                 alt="{{ $product->name }}"
                                 style="width:140px;height:120px;object-fit:cover;border-radius:14px;border:1px solid #E2E8F0;">

                            <label class="role-checkbox-item">
                                <input type="checkbox"
                                       name="remove_main_image"
                                       value="1"
                                       class="role-checkbox">
                                <div class="check-icon"></div>
                                <span class="checkbox-text">Remove Main Image</span>
                            </label>
                        </div>
                    </div>
                @endif

                <div class="field-group">
                    <label class="field-label" for="main_image">
                        {{ $product->main_image ? 'Replace Main Image' : 'Main Image' }}
                    </label>

                    <input type="file"
                           name="main_image"
                           id="main_image"
                           accept="image/*"
                           class="field-input {{ $errors->has('main_image') ? 'error' : '' }}"
                           onchange="previewSingleImage(this, 'mainImagePreview')">

                    @if($errors->has('main_image'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('main_image') }}</p>
                    @endif

                    <div id="mainImagePreview" style="margin-top:12px;"></div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="gallery_images">Add Gallery Images</label>

                    <input type="file"
                           name="gallery_images[]"
                           id="gallery_images"
                           accept="image/*"
                           multiple
                           class="field-input {{ $errors->has('gallery_images') ? 'error' : '' }}"
                           onchange="previewMultipleImages(this, 'galleryImagePreview')">

                    @if($errors->has('gallery_images'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('gallery_images') }}</p>
                    @endif

                    <div id="galleryImagePreview" style="display:flex; gap:12px; flex-wrap:wrap; margin-top:12px;"></div>
                </div>

                @if($product->gallery_images && count($product->gallery_images))
                    <div class="field-group">
                        <label class="field-label">Current Gallery Images</label>

                        <div style="display:flex; gap:14px; flex-wrap:wrap;">
                            @foreach($product->gallery_images as $image)
                                <div style="width:125px;">
                                    <img src="{{ $image['url'] }}"
                                         alt="{{ $image['name'] }}"
                                         style="width:125px;height:105px;object-fit:cover;border-radius:14px;border:1px solid #E2E8F0;">

                                    <form action="{{ route('admin.products.removeMedia', [$product->id, $image['id']]) }}"
                                          method="POST"
                                          style="margin-top:8px;"
                                          onsubmit="return confirm('Are you sure you want to remove this image?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-outline btn-outline-danger" style="width:100%; justify-content:center;">
                                            <i class="fas fa-trash-alt"></i>
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>

                <div>
                    <p class="form-card-title">Product Settings</p>
                    <p class="form-card-subtitle">Update product rules and visibility</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="checkbox-grid">
                    <label class="role-checkbox-item {{ old('try_cloth_available', $product->try_cloth_available) ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="try_cloth_available"
                               value="1"
                               class="role-checkbox"
                               {{ old('try_cloth_available', $product->try_cloth_available) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Try Cloth Available</span>
                    </label>

                    <label class="role-checkbox-item {{ old('return_available', $product->return_available) ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="return_available"
                               value="1"
                               class="role-checkbox"
                               {{ old('return_available', $product->return_available) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Return Available</span>
                    </label>

                    <label class="role-checkbox-item {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="is_featured"
                               value="1"
                               class="role-checkbox"
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Featured Product</span>
                    </label>

                    <label class="role-checkbox-item {{ old('status', $product->status) ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="status"
                               value="1"
                               class="role-checkbox"
                               {{ old('status', $product->status) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Active Status</span>
                    </label>
                </div>

                <div class="form-info-box">
                    <p>
                        <i class="fas fa-info-circle"></i>
                        Try Cloth selected order delivery ke baad non-returnable hoga.
                    </p>
                </div>

            </div>
        </div>

    </div>

    <div class="form-actions-between">
        <div class="form-actions-left">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Save Product
            </button>

            <a href="{{ route('admin.products.index') }}" class="btn-ghost">
                Cancel
            </a>
        </div>

        @can('product_delete')
            <button type="submit"
                    form="delete-product-form"
                    class="btn-danger">
                <i class="fas fa-trash-alt"></i>
                Delete Product
            </button>
        @endcan
    </div>
</form>

@can('product_delete')
    <form id="delete-product-form"
          action="{{ route('admin.products.destroy', $product->id) }}"
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
                     style="width:140px;height:120px;object-fit:cover;border-radius:14px;border:1px solid #E2E8F0;">
            `;
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function previewMultipleImages(input, targetId) {
    const target = document.getElementById(targetId);
    target.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();

            reader.onload = function(e) {
                const box = document.createElement('div');
                box.innerHTML = `
                    <img src="${e.target.result}"
                         style="width:110px;height:95px;object-fit:cover;border-radius:14px;border:1px solid #E2E8F0;">
                `;
                target.appendChild(box);
            };

            reader.readAsDataURL(file);
        });
    }
}
</script>
@endsection