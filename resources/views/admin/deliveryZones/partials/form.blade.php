@php
    $get = fn ($key, $default = '') => old($key, $zone->{$key} ?? $default);
@endphp

<div class="admin-form-grid">
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div><p class="form-card-title">Location</p><p class="form-card-subtitle">City, area and pincode</p></div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="shop_id">Shop</label>
                <select name="shop_id" id="shop_id" class="field-input">
                    @foreach($shops as $id => $entry)
                        <option value="{{ $id }}" {{ (string) $get('shop_id') === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label class="field-label" for="city">City <span class="req">*</span></label>
                <input type="text" name="city" id="city" value="{{ $get('city') }}" required class="field-input {{ $errors->has('city') ? 'error' : '' }}">
            </div>
            <div class="field-group">
                <label class="field-label" for="area">Area</label>
                <input type="text" name="area" id="area" value="{{ $get('area') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="pincode">Pincode <span class="req">*</span></label>
                <input type="text" name="pincode" id="pincode" value="{{ $get('pincode') }}" required class="field-input {{ $errors->has('pincode') ? 'error' : '' }}">
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-bolt"></i></div>
            <div><p class="form-card-title">Quick Delivery Rules</p><p class="form-card-subtitle">Speed, charges and trial settings</p></div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="min_delivery_minutes">Min Delivery Minutes</label>
                <input type="number" min="1" name="min_delivery_minutes" id="min_delivery_minutes" value="{{ $get('min_delivery_minutes', 60) }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="max_delivery_minutes">Max Delivery Minutes</label>
                <input type="number" min="1" name="max_delivery_minutes" id="max_delivery_minutes" value="{{ $get('max_delivery_minutes', 120) }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="delivery_charge">Delivery Charge</label>
                <input type="number" min="0" step="0.01" name="delivery_charge" id="delivery_charge" value="{{ $get('delivery_charge', 0) }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="free_delivery_min_amount">Free Delivery Above</label>
                <input type="number" min="0" step="0.01" name="free_delivery_min_amount" id="free_delivery_min_amount" value="{{ $get('free_delivery_min_amount') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="trial_wait_minutes">Trial Wait Minutes</label>
                <input type="number" min="0" name="trial_wait_minutes" id="trial_wait_minutes" value="{{ $get('trial_wait_minutes', 30) }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="sort_order">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ $get('sort_order', 0) }}" class="field-input">
            </div>
            <div class="checkbox-grid">
                <label class="role-checkbox-item {{ $get('try_first_enabled', 1) ? 'checked' : '' }}"><input type="checkbox" name="try_first_enabled" value="1" class="role-checkbox" {{ $get('try_first_enabled', 1) ? 'checked' : '' }}><div class="check-icon"></div><span class="checkbox-text">Try First Enabled</span></label>
                <label class="role-checkbox-item {{ $get('cod_enabled', 1) ? 'checked' : '' }}"><input type="checkbox" name="cod_enabled" value="1" class="role-checkbox" {{ $get('cod_enabled', 1) ? 'checked' : '' }}><div class="check-icon"></div><span class="checkbox-text">COD Enabled</span></label>
                <label class="role-checkbox-item {{ $get('status', 1) ? 'checked' : '' }}"><input type="checkbox" name="status" value="1" class="role-checkbox" {{ $get('status', 1) ? 'checked' : '' }}><div class="check-icon"></div><span class="checkbox-text">Active</span></label>
            </div>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Zone</button>
    <a href="{{ route('admin.delivery-zones.index') }}" class="btn-ghost">Cancel</a>
</div>
