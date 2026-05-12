@extends('layouts.admin')

@section('page-title', 'Add Product')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.products.index') }}" class="admin-back-link">
            ← {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Add Product</h2>

        <p class="admin-page-subtitle">
            Create a new clothing product with images, stock and return settings
        </p>
    </div>
</div>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="admin-form-grid">

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-tshirt"></i>
                </div>

                <div>
                    <p class="form-card-title">Product Information</p>
                    <p class="form-card-subtitle">Basic product details</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="name">
                        Product Name <span class="req">*</span>
                    </label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-tag icon"></i>

                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               required
                               placeholder="Example: Premium Cotton Shirt"
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
                               value="{{ old('slug') }}"
                               placeholder="premium-cotton-shirt"
                               class="field-input {{ $errors->has('slug') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('slug'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('slug') }}</p>
                    @else
                        <p class="field-hint">Blank rakhne par slug auto generate hoga.</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="sku">SKU</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-barcode icon"></i>

                        <input type="text"
                               name="sku"
                               id="sku"
                               value="{{ old('sku') }}"
                               placeholder="SKU-001"
                               class="field-input {{ $errors->has('sku') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('sku'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('sku') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="shop_id">Shop</label>

                    <select name="shop_id"
                            id="shop_id"
                            class="field-input {{ $errors->has('shop_id') ? 'error' : '' }}">
                        @foreach($shops as $id => $entry)
                            <option value="{{ $id }}" {{ old('shop_id') == $id ? 'selected' : '' }}>
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

                    <select name="category_id"
                            id="category_id"
                            class="field-input {{ $errors->has('category_id') ? 'error' : '' }}">
                        @foreach($categories as $id => $entry)
                            <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
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
                              placeholder="Small product summary"
                              class="field-input {{ $errors->has('short_description') ? 'error' : '' }}">{{ old('short_description') }}</textarea>

                    @if($errors->has('short_description'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('short_description') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="description">Description</label>

                    <textarea name="description"
                              id="description"
                              rows="5"
                              placeholder="Full product details"
                              class="field-input {{ $errors->has('description') ? 'error' : '' }}">{{ old('description') }}</textarea>

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
                    <p class="form-card-subtitle">Pricing and inventory details</p>
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
                               value="{{ old('price') }}"
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
                               value="{{ old('discount_price') }}"
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
                               value="{{ old('stock_quantity', 0) }}"
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
                               value="{{ old('brand') }}"
                               class="field-input {{ $errors->has('brand') ? 'error' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="fabric">Fabric</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-layer-group icon"></i>

                        <input type="text"
                               name="fabric"
                               id="fabric"
                               value="{{ old('fabric') }}"
                               placeholder="Cotton, Silk, Denim..."
                               class="field-input {{ $errors->has('fabric') ? 'error' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="sort_order">Sort Order</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-sort-numeric-up icon"></i>

                        <input type="number"
                               name="sort_order"
                               id="sort_order"
                               value="{{ old('sort_order', 0) }}"
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
                    <p class="form-card-subtitle">Upload main image and gallery images</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="main_image">Main Image</label>

                    <input type="file"
                           name="main_image"
                           id="main_image"
                           accept="image/*"
                           class="field-input {{ $errors->has('main_image') ? 'error' : '' }}"
                           onchange="previewSingleImage(this, 'mainImagePreview')">

                    @if($errors->has('main_image'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('main_image') }}</p>
                    @else
                        <p class="field-hint">Recommended: JPG, PNG, WEBP. Max 4MB.</p>
                    @endif

                    <div id="mainImagePreview" style="margin-top:12px;"></div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="gallery_images">Gallery Images</label>

                    <input type="file"
                           name="gallery_images[]"
                           id="gallery_images"
                           accept="image/*"
                           multiple
                           class="field-input {{ $errors->has('gallery_images') ? 'error' : '' }}"
                           onchange="previewMultipleImages(this, 'galleryImagePreview')">

                    @if($errors->has('gallery_images'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('gallery_images') }}</p>
                    @else
                        <p class="field-hint">Multiple product images select kar sakte ho.</p>
                    @endif

                    <div id="galleryImagePreview" style="display:flex; gap:12px; flex-wrap:wrap; margin-top:12px;"></div>
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>

                <div>
                    <p class="form-card-title">Product Settings</p>
                    <p class="form-card-subtitle">Return, try cloth and visibility settings</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="checkbox-grid">
                    <label class="role-checkbox-item checked">
                        <input type="checkbox"
                               name="try_cloth_available"
                               value="1"
                               class="role-checkbox"
                               {{ old('try_cloth_available', 1) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Try Cloth Available</span>
                    </label>

                    <label class="role-checkbox-item checked">
                        <input type="checkbox"
                               name="return_available"
                               value="1"
                               class="role-checkbox"
                               {{ old('return_available', 1) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Return Available</span>
                    </label>

                    <label class="role-checkbox-item">
                        <input type="checkbox"
                               name="is_featured"
                               value="1"
                               class="role-checkbox"
                               {{ old('is_featured') ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Featured Product</span>
                    </label>

                    <label class="role-checkbox-item checked">
                        <input type="checkbox"
                               name="status"
                               value="1"
                               class="role-checkbox"
                               {{ old('status', 1) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Active Status</span>
                    </label>
                </div>

                <div class="form-info-box">
                    <p>
                        <i class="fas fa-info-circle"></i>
                        Agar customer checkout par Try Cloth select karega to order non-returnable ho jayega.
                    </p>
                </div>

            </div>
        </div>

    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="fas fa-check"></i>
            Save Product
        </button>

        <a href="{{ route('admin.products.index') }}" class="btn-ghost">
            Cancel
        </a>
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