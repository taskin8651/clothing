@extends('layouts.admin')

@section('page-title', 'Edit Customer Address')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.customer-addresses.index') }}" class="admin-back-link">
            &larr; {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Edit Customer Address</h2>

        <p class="admin-page-subtitle">
            Update delivery address and map matching details
        </p>
    </div>

    <div class="identity-card">
        <div class="identity-avatar" style="background:#4F46E5;">
            <i class="fas fa-map-marker-alt"></i>
        </div>

        <div>
            <p class="identity-title">{{ optional($customerAddress->customer)->name ?? 'Customer Address' }}</p>
            <p class="identity-subtitle">Address ID #{{ $customerAddress->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.customer-addresses.update', $customerAddress->id) }}">
    @method('PUT')
    @csrf

    <div class="admin-form-grid">

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>

                <div>
                    <p class="form-card-title">Customer & Receiver</p>
                    <p class="form-card-subtitle">Update customer and receiver details</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="customer_id">
                        Customer <span class="req">*</span>
                    </label>

                    <select name="customer_id"
                            id="customer_id"
                            required
                            class="field-input {{ $errors->has('customer_id') ? 'error' : '' }}">
                        @foreach($customers as $id => $entry)
                            <option value="{{ $id }}" {{ (string) old('customer_id', $customerAddress->customer_id) === (string) $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>

                    @if($errors->has('customer_id'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('customer_id') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="name">Receiver Name</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-user icon"></i>

                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $customerAddress->name) }}"
                               placeholder="Receiver name"
                               class="field-input {{ $errors->has('name') ? 'error' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="mobile">Receiver Mobile</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-phone icon"></i>

                        <input type="text"
                               name="mobile"
                               id="mobile"
                               value="{{ old('mobile', $customerAddress->mobile) }}"
                               placeholder="Receiver mobile"
                               class="field-input {{ $errors->has('mobile') ? 'error' : '' }}">
                    </div>
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-map"></i>
                </div>

                <div>
                    <p class="form-card-title">Address Details</p>
                    <p class="form-card-subtitle">Area and pincode for delivery matching</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="address">
                        Address <span class="req">*</span>
                    </label>

                    <textarea name="address"
                              id="address"
                              rows="4"
                              required
                              class="field-input {{ $errors->has('address') ? 'error' : '' }}">{{ old('address', $customerAddress->address) }}</textarea>

                    @if($errors->has('address'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('address') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="landmark">Landmark</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-map-signs icon"></i>

                        <input type="text"
                               name="landmark"
                               id="landmark"
                               value="{{ old('landmark', $customerAddress->landmark) }}"
                               class="field-input {{ $errors->has('landmark') ? 'error' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="city">City</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-city icon"></i>

                        <input type="text"
                               name="city"
                               id="city"
                               value="{{ old('city', $customerAddress->city) }}"
                               class="field-input {{ $errors->has('city') ? 'error' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="area">Area</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-map icon"></i>

                        <input type="text"
                               name="area"
                               id="area"
                               value="{{ old('area', $customerAddress->area) }}"
                               class="field-input {{ $errors->has('area') ? 'error' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="pincode">Pincode</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-map-pin icon"></i>

                        <input type="text"
                               name="pincode"
                               id="pincode"
                               value="{{ old('pincode', $customerAddress->pincode) }}"
                               class="field-input {{ $errors->has('pincode') ? 'error' : '' }}">
                    </div>
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-location-arrow"></i>
                </div>

                <div>
                    <p class="form-card-title">Map Coordinates</p>
                    <p class="form-card-subtitle">Optional latitude and longitude</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="latitude">Latitude</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-location-arrow icon"></i>

                        <input type="text"
                               name="latitude"
                               id="latitude"
                               value="{{ old('latitude', $customerAddress->latitude) }}"
                               class="field-input {{ $errors->has('latitude') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('latitude'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('latitude') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="longitude">Longitude</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-location-arrow icon"></i>

                        <input type="text"
                               name="longitude"
                               id="longitude"
                               value="{{ old('longitude', $customerAddress->longitude) }}"
                               class="field-input {{ $errors->has('longitude') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('longitude'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('longitude') }}
                        </p>
                    @endif
                </div>

                <div class="checkbox-grid">
                    <label class="role-checkbox-item {{ old('is_default', $customerAddress->is_default) ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="is_default"
                               value="1"
                               class="role-checkbox"
                               {{ old('is_default', $customerAddress->is_default) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Default Address</span>
                    </label>

                    <label class="role-checkbox-item {{ old('status', $customerAddress->status) ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="status"
                               value="1"
                               class="role-checkbox"
                               {{ old('status', $customerAddress->status) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Active Status</span>
                    </label>
                </div>

                <div class="form-info-box">
                    <p>
                        <i class="fas fa-info-circle"></i>
                        Default address set karne par isi customer ke old default addresses remove ho jayenge.
                    </p>
                </div>

            </div>
        </div>

    </div>

    <div class="form-actions-between">
        <div class="form-actions-left">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Save Address
            </button>

            <a href="{{ route('admin.customer-addresses.index') }}" class="btn-ghost">
                Cancel
            </a>
        </div>

        @can('customer_address_delete')
            <button type="submit"
                    form="delete-address-form"
                    class="btn-danger">
                <i class="fas fa-trash-alt"></i>
                Delete Address
            </button>
        @endcan
    </div>
</form>

@can('customer_address_delete')
    <form id="delete-address-form"
          action="{{ route('admin.customer-addresses.destroy', $customerAddress->id) }}"
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
document.querySelectorAll('.role-checkbox-item input[type="checkbox"]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        const label = this.closest('.role-checkbox-item');
        this.checked ? label.classList.add('checked') : label.classList.remove('checked');
    });
});
</script>
@endsection
