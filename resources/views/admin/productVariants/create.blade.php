@extends('layouts.admin')

@section('page-title', 'Add Product Variant')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.product-variants.index') }}" class="admin-back-link">
            ← {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Add Product Variant</h2>

        <p class="admin-page-subtitle">
            Create size/color wise stock variant for product
        </p>
    </div>
</div>

<form method="POST" action="{{ route('admin.product-variants.store') }}">
    @csrf

    <div class="admin-form-grid">

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>

                <div>
                    <p class="form-card-title">Variant Information</p>
                    <p class="form-card-subtitle">Product, size, color and SKU</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="product_id">Product <span class="req">*</span></label>

                    <select name="product_id"
                            id="product_id"
                            required
                            class="field-input {{ $errors->has('product_id') ? 'error' : '' }}">
                        @foreach($products as $id => $entry)
                            <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>

                    @if($errors->has('product_id'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('product_id') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="size">Size</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-ruler icon"></i>

                        <input type="text"
                               name="size"
                               id="size"
                               value="{{ old('size') }}"
                               placeholder="S, M, L, XL, 32, 34..."
                               class="field-input {{ $errors->has('size') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('size'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('size') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="color">Color</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-palette icon"></i>

                        <input type="text"
                               name="color"
                               id="color"
                               value="{{ old('color') }}"
                               placeholder="Black, White, Blue..."
                               class="field-input {{ $errors->has('color') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('color'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('color') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="sku">Variant SKU</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-barcode icon"></i>

                        <input type="text"
                               name="sku"
                               id="sku"
                               value="{{ old('sku') }}"
                               placeholder="SKU-M-BLACK"
                               class="field-input {{ $errors->has('sku') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('sku'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('sku') }}
                        </p>
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
                    <p class="form-card-subtitle">Optional variant price and inventory</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="price">Variant Price</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-rupee-sign icon"></i>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="price"
                               id="price"
                               value="{{ old('price') }}"
                               placeholder="Blank = product price"
                               class="field-input {{ $errors->has('price') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('price'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('price') }}
                        </p>
                    @else
                        <p class="field-hint">Blank rakhne par product price use kar sakte ho.</p>
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
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('discount_price') }}
                        </p>
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
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('stock_quantity') }}
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
                               value="{{ old('sort_order', 0) }}"
                               class="field-input {{ $errors->has('sort_order') ? 'error' : '' }}">
                    </div>
                </div>

                <div class="checkbox-grid">
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
                        Order ke time selected size/color ka stock isi variant se minus hoga.
                    </p>
                </div>

            </div>
        </div>

    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="fas fa-check"></i>
            Save Variant
        </button>

        <a href="{{ route('admin.product-variants.index') }}" class="btn-ghost">
            Cancel
        </a>
    </div>

</form>

@endsection