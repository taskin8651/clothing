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
            Update product details, images, variants, stock and return settings
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
            <p class="identity-subtitle">Product ID #{{ $product->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf

    <div class="admin-form-grid">

        {{-- PRODUCT INFORMATION --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-tshirt"></i>
                </div>

                <div>
                    <p class="form-card-title">Product Information</p>
                    <p class="form-card-subtitle">Update basic product details</p>
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
                               value="{{ old('name', $product->name) }}"
                               required
                               placeholder="Example: Premium Cotton Shirt"
                               class="field-input {{ $errors->has('name') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('name'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('name') }}
                        </p>
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
                               placeholder="premium-cotton-shirt"
                               class="field-input {{ $errors->has('slug') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('slug'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('slug') }}
                        </p>
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
                               value="{{ old('sku', $product->sku) }}"
                               placeholder="SKU-001"
                               class="field-input {{ $errors->has('sku') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('sku'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('sku') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="shop_id">Shop</label>

                    <select name="shop_id"
                            id="shop_id"
                            class="field-input {{ $errors->has('shop_id') ? 'error' : '' }}">
                        @foreach($shops as $id => $entry)
                            <option value="{{ $id }}" {{ (string) old('shop_id', $product->shop_id) === (string) $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>

                    @if($errors->has('shop_id'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('shop_id') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="category_id">Category</label>

                    <select name="category_id"
                            id="category_id"
                            class="field-input {{ $errors->has('category_id') ? 'error' : '' }}">
                        @foreach($categories as $id => $entry)
                            <option value="{{ $id }}" {{ (string) old('category_id', $product->category_id) === (string) $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>

                    @if($errors->has('category_id'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('category_id') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="short_description">Short Description</label>

                    <textarea name="short_description"
                              id="short_description"
                              rows="3"
                              placeholder="Small product summary"
                              class="field-input {{ $errors->has('short_description') ? 'error' : '' }}">{{ old('short_description', $product->short_description) }}</textarea>

                    @if($errors->has('short_description'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('short_description') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="description">Description</label>

                    <textarea name="description"
                              id="description"
                              rows="5"
                              placeholder="Full product details"
                              class="field-input {{ $errors->has('description') ? 'error' : '' }}">{{ old('description', $product->description) }}</textarea>

                    @if($errors->has('description'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('description') }}
                        </p>
                    @endif
                </div>

            </div>
        </div>

        {{-- PRICE & STOCK --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-rupee-sign"></i>
                </div>

                <div>
                    <p class="form-card-title">Price & Stock</p>
                    <p class="form-card-subtitle">Update base pricing and inventory</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="price">
                        Price <span class="req">*</span>
                    </label>

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
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('price') }}
                        </p>
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
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('discount_price') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="stock_quantity">Base Stock Quantity</label>

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
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('stock_quantity') }}
                        </p>
                    @else
                        <p class="field-hint">Variant stock use karoge to base stock optional rahega.</p>
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
                               placeholder="Example: Raymond, Zara, Local Brand"
                               class="field-input {{ $errors->has('brand') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('brand'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('brand') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="fabric">Fabric</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-layer-group icon"></i>

                        <input type="text"
                               name="fabric"
                               id="fabric"
                               value="{{ old('fabric', $product->fabric) }}"
                               placeholder="Cotton, Silk, Denim..."
                               class="field-input {{ $errors->has('fabric') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('fabric'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('fabric') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="sort_order">Sort Order</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-sort-numeric-up icon"></i>

                        <input type="number"
                               name="sort_order"
                               id="sort_order"
                               value="{{ old('sort_order', $product->sort_order) }}"
                               class="field-input {{ $errors->has('sort_order') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('sort_order'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('sort_order') }}
                        </p>
                    @endif
                </div>

            </div>
        </div>

        {{-- PRODUCT IMAGES --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-image"></i>
                </div>

                <div>
                    <p class="form-card-title">Product Images</p>
                    <p class="form-card-subtitle">Replace main image or add/remove gallery images</p>
                </div>
            </div>

            <div class="form-card-body">

                @if($product->main_image)
                    <div class="field-group">
                        <label class="field-label">Current Main Image</label>

                        <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                            <img src="{{ $product->main_image['url'] }}"
                                 alt="{{ $product->name }}"
                                 style="width:150px;height:125px;object-fit:cover;border-radius:16px;border:1px solid #E2E8F0;">

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
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('main_image') }}
                        </p>
                    @else
                        <p class="field-hint">Recommended: JPG, PNG, WEBP. Max 4MB.</p>
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
                           class="field-input {{ $errors->has('gallery_images') || $errors->has('gallery_images.*') ? 'error' : '' }}"
                           onchange="previewMultipleImages(this, 'galleryImagePreview')">

                    @if($errors->has('gallery_images'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('gallery_images') }}
                        </p>
                    @elseif($errors->has('gallery_images.*'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('gallery_images.*') }}
                        </p>
                    @else
                        <p class="field-hint">New gallery images upload kar sakte ho.</p>
                    @endif

                    <div id="galleryImagePreview"
                         style="display:flex; gap:12px; flex-wrap:wrap; margin-top:12px;"></div>
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

                                        <button type="submit"
                                                class="btn-outline btn-outline-danger"
                                                style="width:100%; justify-content:center;">
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

        {{-- PRODUCT VARIANTS --}}
        <div class="form-card">
            <div class="form-card-header between">
                <div class="form-card-head-left">
                    <div class="form-card-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>

                    <div>
                        <p class="form-card-title">Product Variants</p>
                        <p class="form-card-subtitle">Update size, color, SKU, price and stock</p>
                    </div>
                </div>

                <button type="button" class="btn-mini-primary" onclick="addVariantRow()">
                    <i class="fas fa-plus"></i>
                    Add
                </button>
            </div>

            <div class="form-card-body">

                <div id="variantRows">

                    @php
                        $existingVariants = $product->variants ?? collect();

                        $oldVariants = old('variants');

                        if ($oldVariants === null) {
                            if ($existingVariants->count()) {
                                $oldVariants = $existingVariants->map(function ($variant) {
                                    return [
                                        'id' => $variant->id,
                                        'size' => $variant->size,
                                        'color' => $variant->color,
                                        'sku' => $variant->sku,
                                        'price' => $variant->price,
                                        'discount_price' => $variant->discount_price,
                                        'stock_quantity' => $variant->stock_quantity,
                                        'sort_order' => $variant->sort_order,
                                        'status' => $variant->status ? 1 : 0,
                                    ];
                                })->toArray();
                            } else {
                                $oldVariants = [
                                    [
                                        'id' => '',
                                        'size' => '',
                                        'color' => '',
                                        'sku' => '',
                                        'price' => '',
                                        'discount_price' => '',
                                        'stock_quantity' => 0,
                                        'sort_order' => 0,
                                        'status' => 1,
                                    ]
                                ];
                            }
                        }
                    @endphp

                    @foreach($oldVariants as $index => $variant)
                        <div class="variant-row" data-index="{{ $index }}">
                            <div class="variant-row-head">
                                <p>
                                    <i class="fas fa-sliders-h"></i>
                                    Variant #<span class="variant-number">{{ $index + 1 }}</span>
                                </p>

                                <button type="button"
                                        class="btn-mini-ghost text-danger"
                                        onclick="removeVariantRow(this)">
                                    <i class="fas fa-trash-alt"></i>
                                    Remove
                                </button>
                            </div>

                            <input type="hidden"
                                   name="variants[{{ $index }}][id]"
                                   value="{{ $variant['id'] ?? '' }}">

                            <input type="hidden"
                                   name="variants[{{ $index }}][delete]"
                                   value="0"
                                   class="variant-delete-input">

                            <div class="variant-grid">

                                <div class="field-group">
                                    <label class="field-label">Size</label>

                                    <input type="text"
                                           name="variants[{{ $index }}][size]"
                                           value="{{ $variant['size'] ?? '' }}"
                                           placeholder="S, M, L, XL, 32..."
                                           class="field-input {{ $errors->has('variants.' . $index . '.size') ? 'error' : '' }}">

                                    @if($errors->has('variants.' . $index . '.size'))
                                        <p class="field-error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $errors->first('variants.' . $index . '.size') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Color</label>

                                    <input type="text"
                                           name="variants[{{ $index }}][color]"
                                           value="{{ $variant['color'] ?? '' }}"
                                           placeholder="Black, White, Blue..."
                                           class="field-input {{ $errors->has('variants.' . $index . '.color') ? 'error' : '' }}">

                                    @if($errors->has('variants.' . $index . '.color'))
                                        <p class="field-error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $errors->first('variants.' . $index . '.color') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Variant SKU</label>

                                    <input type="text"
                                           name="variants[{{ $index }}][sku]"
                                           value="{{ $variant['sku'] ?? '' }}"
                                           placeholder="SKU-M-BLACK"
                                           class="field-input {{ $errors->has('variants.' . $index . '.sku') ? 'error' : '' }}">

                                    @if($errors->has('variants.' . $index . '.sku'))
                                        <p class="field-error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $errors->first('variants.' . $index . '.sku') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Price</label>

                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="variants[{{ $index }}][price]"
                                           value="{{ $variant['price'] ?? '' }}"
                                           placeholder="Blank = product price"
                                           class="field-input {{ $errors->has('variants.' . $index . '.price') ? 'error' : '' }}">

                                    @if($errors->has('variants.' . $index . '.price'))
                                        <p class="field-error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $errors->first('variants.' . $index . '.price') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Discount Price</label>

                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="variants[{{ $index }}][discount_price]"
                                           value="{{ $variant['discount_price'] ?? '' }}"
                                           class="field-input {{ $errors->has('variants.' . $index . '.discount_price') ? 'error' : '' }}">

                                    @if($errors->has('variants.' . $index . '.discount_price'))
                                        <p class="field-error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $errors->first('variants.' . $index . '.discount_price') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Stock</label>

                                    <input type="number"
                                           min="0"
                                           name="variants[{{ $index }}][stock_quantity]"
                                           value="{{ $variant['stock_quantity'] ?? 0 }}"
                                           class="field-input {{ $errors->has('variants.' . $index . '.stock_quantity') ? 'error' : '' }}">

                                    @if($errors->has('variants.' . $index . '.stock_quantity'))
                                        <p class="field-error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $errors->first('variants.' . $index . '.stock_quantity') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Sort Order</label>

                                    <input type="number"
                                           name="variants[{{ $index }}][sort_order]"
                                           value="{{ $variant['sort_order'] ?? 0 }}"
                                           class="field-input {{ $errors->has('variants.' . $index . '.sort_order') ? 'error' : '' }}">

                                    @if($errors->has('variants.' . $index . '.sort_order'))
                                        <p class="field-error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $errors->first('variants.' . $index . '.sort_order') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Status</label>

                                    <label class="role-checkbox-item {{ !empty($variant['status']) ? 'checked' : '' }}">
                                        <input type="checkbox"
                                               name="variants[{{ $index }}][status]"
                                               value="1"
                                               class="role-checkbox"
                                               {{ !empty($variant['status']) ? 'checked' : '' }}>
                                        <div class="check-icon"></div>
                                        <span class="checkbox-text">Active</span>
                                    </label>
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="form-info-box">
                    <p>
                        <i class="fas fa-info-circle"></i>
                        Existing variant remove karne par update ke time delete ho jayega. New variant blank hoga to save nahi hoga.
                    </p>
                </div>

            </div>
        </div>

        {{-- PRODUCT SETTINGS --}}
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

                @php
                    $tryCloth = old('try_cloth_available', $product->try_cloth_available);
                    $returnAvailable = old('return_available', $product->return_available);
                    $featured = old('is_featured', $product->is_featured);
                    $status = old('status', $product->status);
                @endphp

                <div class="checkbox-grid">
                    <label class="role-checkbox-item {{ $tryCloth ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="try_cloth_available"
                               value="1"
                               class="role-checkbox"
                               {{ $tryCloth ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Try Cloth Available</span>
                    </label>

                    <label class="role-checkbox-item {{ $returnAvailable ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="return_available"
                               value="1"
                               class="role-checkbox"
                               {{ $returnAvailable ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Return Available</span>
                    </label>

                    <label class="role-checkbox-item {{ $featured ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="is_featured"
                               value="1"
                               class="role-checkbox"
                               {{ $featured ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Featured Product</span>
                    </label>

                    <label class="role-checkbox-item {{ $status ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="status"
                               value="1"
                               class="role-checkbox"
                               {{ $status ? 'checked' : '' }}>
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
function makeSlug(value) {
    return value
        .toString()
        .toLowerCase()
        .trim()
        .replace(/&/g, 'and')
        .replace(/[\s\W-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

const slugInput = document.getElementById('slug');

if (slugInput) {
    slugInput.addEventListener('input', function () {
        this.value = makeSlug(this.value);
    });
}

function previewSingleImage(input, targetId) {
    const target = document.getElementById(targetId);
    target.innerHTML = '';

    if (!input.files || !input.files[0]) {
        return;
    }

    const file = input.files[0];

    if (!file.type.startsWith('image/')) {
        target.innerHTML = '<p class="field-error"><i class="fas fa-exclamation-circle"></i> Please select a valid image.</p>';
        input.value = '';
        return;
    }

    const reader = new FileReader();

    reader.onload = function(e) {
        target.innerHTML = `
            <div style="position:relative; width:150px;">
                <img src="${e.target.result}"
                     style="width:150px;height:125px;object-fit:cover;border-radius:16px;border:1px solid #E2E8F0;">
                <button type="button"
                        onclick="clearSingleImage('${input.id}', '${targetId}')"
                        style="position:absolute;top:6px;right:6px;border:0;border-radius:999px;background:#EF4444;color:#fff;width:26px;height:26px;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                    ×
                </button>
            </div>
        `;
    };

    reader.readAsDataURL(file);
}

function clearSingleImage(inputId, targetId) {
    document.getElementById(inputId).value = '';
    document.getElementById(targetId).innerHTML = '';
}

function previewMultipleImages(input, targetId) {
    const target = document.getElementById(targetId);
    target.innerHTML = '';

    if (!input.files || input.files.length === 0) {
        return;
    }

    Array.from(input.files).forEach(file => {
        if (!file.type.startsWith('image/')) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {
            const box = document.createElement('div');
            box.style.position = 'relative';
            box.style.width = '115px';

            box.innerHTML = `
                <img src="${e.target.result}"
                     style="width:115px;height:100px;object-fit:cover;border-radius:14px;border:1px solid #E2E8F0;">
            `;

            target.appendChild(box);
        };

        reader.readAsDataURL(file);
    });
}

let variantIndex = document.querySelectorAll('.variant-row').length;

function addVariantRow() {
    const wrapper = document.getElementById('variantRows');

    const html = `
        <div class="variant-row" data-index="${variantIndex}">
            <div class="variant-row-head">
                <p>
                    <i class="fas fa-sliders-h"></i>
                    Variant #<span class="variant-number">${variantIndex + 1}</span>
                </p>

                <button type="button"
                        class="btn-mini-ghost text-danger"
                        onclick="removeVariantRow(this)">
                    <i class="fas fa-trash-alt"></i>
                    Remove
                </button>
            </div>

            <input type="hidden"
                   name="variants[${variantIndex}][id]"
                   value="">

            <input type="hidden"
                   name="variants[${variantIndex}][delete]"
                   value="0"
                   class="variant-delete-input">

            <div class="variant-grid">

                <div class="field-group">
                    <label class="field-label">Size</label>
                    <input type="text"
                           name="variants[${variantIndex}][size]"
                           placeholder="S, M, L, XL, 32..."
                           class="field-input">
                </div>

                <div class="field-group">
                    <label class="field-label">Color</label>
                    <input type="text"
                           name="variants[${variantIndex}][color]"
                           placeholder="Black, White, Blue..."
                           class="field-input">
                </div>

                <div class="field-group">
                    <label class="field-label">Variant SKU</label>
                    <input type="text"
                           name="variants[${variantIndex}][sku]"
                           placeholder="SKU-M-BLACK"
                           class="field-input">
                </div>

                <div class="field-group">
                    <label class="field-label">Price</label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           name="variants[${variantIndex}][price]"
                           placeholder="Blank = product price"
                           class="field-input">
                </div>

                <div class="field-group">
                    <label class="field-label">Discount Price</label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           name="variants[${variantIndex}][discount_price]"
                           class="field-input">
                </div>

                <div class="field-group">
                    <label class="field-label">Stock</label>
                    <input type="number"
                           min="0"
                           name="variants[${variantIndex}][stock_quantity]"
                           value="0"
                           class="field-input">
                </div>

                <div class="field-group">
                    <label class="field-label">Sort Order</label>
                    <input type="number"
                           name="variants[${variantIndex}][sort_order]"
                           value="0"
                           class="field-input">
                </div>

                <div class="field-group">
                    <label class="field-label">Status</label>

                    <label class="role-checkbox-item checked">
                        <input type="checkbox"
                               name="variants[${variantIndex}][status]"
                               value="1"
                               class="role-checkbox"
                               checked>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Active</span>
                    </label>
                </div>

            </div>
        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);

    variantIndex++;
    refreshVariantNumbers();
    bindCheckboxStyle();
}

function removeVariantRow(button) {
    const rows = document.querySelectorAll('.variant-row');
    const row = button.closest('.variant-row');
    const deleteInput = row.querySelector('.variant-delete-input');
    const variantIdInput = row.querySelector('input[name$="[id]"]');

    if (rows.length <= 1) {
        alert('At least one variant row required.');
        return;
    }

    if (variantIdInput && variantIdInput.value) {
        deleteInput.value = '1';
        row.style.display = 'none';
    } else {
        row.remove();
    }

    refreshVariantNumbers();
}

function refreshVariantNumbers() {
    let visibleIndex = 1;

    document.querySelectorAll('.variant-row').forEach(function(row) {
        if (row.style.display === 'none') {
            return;
        }

        const number = row.querySelector('.variant-number');

        if (number) {
            number.textContent = visibleIndex;
            visibleIndex++;
        }
    });
}

function bindCheckboxStyle() {
    document.querySelectorAll('.role-checkbox-item input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.onchange = function () {
            const label = this.closest('.role-checkbox-item');

            if (this.checked) {
                label.classList.add('checked');
            } else {
                label.classList.remove('checked');
            }
        };
    });
}

bindCheckboxStyle();
</script>

<style>
.variant-row{
    border:1px solid #E2E8F0;
    background:#FFFFFF;
    border-radius:18px;
    padding:16px;
    margin-bottom:14px;
    box-shadow:0 10px 24px rgba(15,23,42,.04);
}

.variant-row-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding-bottom:12px;
    margin-bottom:14px;
    border-bottom:1px solid #EEF2F7;
}

.variant-row-head p{
    margin:0;
    font-size:13px;
    font-weight:800;
    color:#0F172A;
    display:flex;
    align-items:center;
    gap:8px;
}

.variant-row-head p i{
    color:#4F46E5;
}

.variant-grid{
    display:grid;
    grid-template-columns:repeat(4, minmax(0, 1fr));
    gap:14px;
}

.text-danger{
    color:#DC2626 !important;
}

@media(max-width: 1199px){
    .variant-grid{
        grid-template-columns:repeat(3, minmax(0, 1fr));
    }
}

@media(max-width: 991px){
    .variant-grid{
        grid-template-columns:repeat(2, minmax(0, 1fr));
    }
}

@media(max-width: 575px){
    .variant-grid{
        grid-template-columns:1fr;
    }

    .variant-row-head{
        align-items:flex-start;
        flex-direction:column;
    }
}
</style>

@endsection