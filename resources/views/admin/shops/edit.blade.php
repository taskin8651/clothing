@extends('layouts.admin')

@section('page-title', 'Edit Shop')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.shops.index') }}" class="admin-back-link">
            ← {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Edit Shop</h2>

        <p class="admin-page-subtitle">Update shop profile and delivery location details</p>
    </div>

    <div class="identity-card">
        <div class="identity-avatar" style="background:#F8FAFC; border:1px solid #E2E8F0; overflow:hidden;">
            @if($shop->shop_image)
                <img src="{{ $shop->shop_image['url'] }}" alt="{{ $shop->shop_name }}" style="width:100%; height:100%; object-fit:cover;">
            @else
                <i class="fas fa-store" style="color:#64748B;"></i>
            @endif
        </div>

        <div>
            <p class="identity-title">{{ $shop->shop_name }}</p>
            <p class="identity-subtitle">ID #{{ $shop->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.shops.update', $shop->id) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf

    <div class="admin-form-grid">

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-store"></i>
                </div>

                <div>
                    <p class="form-card-title">Shop Information</p>
                    <p class="form-card-subtitle">Update shop and owner details</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="shop_name">Shop Name <span class="req">*</span></label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-store icon"></i>
                        <input type="text" name="shop_name" id="shop_name" value="{{ old('shop_name', $shop->shop_name) }}" required
                               class="field-input {{ $errors->has('shop_name') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('shop_name'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('shop_name') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="owner_name">Owner Name</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-user icon"></i>
                        <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name', $shop->owner_name) }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="mobile">Mobile</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-phone icon"></i>
                        <input type="text" name="mobile" id="mobile" value="{{ old('mobile', $shop->mobile) }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="email">Email</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" name="email" id="email" value="{{ old('email', $shop->email) }}"
                               class="field-input {{ $errors->has('email') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('email'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('email') }}</p>
                    @endif
                </div>

                @if($shop->shop_image)
                    <div class="field-group">
                        <label class="field-label">Current Shop Image</label>

                        <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                            <img src="{{ $shop->shop_image['url'] }}"
                                 alt="{{ $shop->shop_name }}"
                                 style="width:150px;height:125px;object-fit:cover;border-radius:16px;border:1px solid #E2E8F0;">

                            <label class="role-checkbox-item">
                                <input type="checkbox" name="remove_shop_image" value="1" class="role-checkbox">
                                <div class="check-icon"></div>
                                <span class="checkbox-text">Remove Image</span>
                            </label>
                        </div>
                    </div>
                @endif

                <div class="field-group">
                    <label class="field-label" for="shop_image">
                        {{ $shop->shop_image ? 'Replace Shop Image' : 'Shop Image' }}
                    </label>

                    <input type="file" name="shop_image" id="shop_image" accept="image/*"
                           class="field-input {{ $errors->has('shop_image') ? 'error' : '' }}"
                           onchange="previewSingleImage(this, 'shopImagePreview')">

                    @if($errors->has('shop_image'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('shop_image') }}</p>
                    @endif

                    <div id="shopImagePreview" style="margin-top:12px;"></div>
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>

                <div>
                    <p class="form-card-title">Location Details</p>
                    <p class="form-card-subtitle">Used for nearest shop assignment</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="address">Address</label>
                    <textarea name="address" id="address" rows="4" class="field-input">{{ old('address', $shop->address) }}</textarea>
                </div>

                <div class="field-group">
                    <label class="field-label" for="city">City</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-city icon"></i>
                        <input type="text" name="city" id="city" value="{{ old('city', $shop->city) }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="area">Area</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-map icon"></i>
                        <input type="text" name="area" id="area" value="{{ old('area', $shop->area) }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="pincode">Pincode</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-map-pin icon"></i>
                        <input type="text" name="pincode" id="pincode" value="{{ old('pincode', $shop->pincode) }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="latitude">Latitude</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-location-arrow icon"></i>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $shop->latitude) }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="longitude">Longitude</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-location-arrow icon"></i>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $shop->longitude) }}" class="field-input">
                    </div>
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-clock"></i>
                </div>

                <div>
                    <p class="form-card-title">Shop Settings</p>
                    <p class="form-card-subtitle">Timing and status</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="opening_time">Opening Time</label>
                    <input type="time" name="opening_time" id="opening_time" value="{{ old('opening_time', $shop->opening_time) }}" class="field-input">
                </div>

                <div class="field-group">
                    <label class="field-label" for="closing_time">Closing Time</label>
                    <input type="time" name="closing_time" id="closing_time" value="{{ old('closing_time', $shop->closing_time) }}" class="field-input">
                </div>

                <div class="field-group">
                    <label class="field-label" for="sort_order">Sort Order</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-sort-numeric-up icon"></i>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $shop->sort_order) }}" class="field-input">
                    </div>
                </div>

                <div class="checkbox-grid">
                    <label class="role-checkbox-item {{ old('status', $shop->status) ? 'checked' : '' }}">
                        <input type="checkbox" name="status" value="1" class="role-checkbox" {{ old('status', $shop->status) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Active Status</span>
                    </label>
                </div>

            </div>
        </div>

    </div>

    <div class="form-actions-between">
        <div class="form-actions-left">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Save Shop
            </button>

            <a href="{{ route('admin.shops.index') }}" class="btn-ghost">Cancel</a>
        </div>

        @can('shop_delete')
            <button type="submit" form="delete-shop-form" class="btn-danger">
                <i class="fas fa-trash-alt"></i>
                Delete Shop
            </button>
        @endcan
    </div>
</form>

@can('shop_delete')
    <form id="delete-shop-form"
          action="{{ route('admin.shops.destroy', $shop->id) }}"
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