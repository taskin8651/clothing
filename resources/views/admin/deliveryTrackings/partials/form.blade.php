<div class="form-card">
    <div class="form-card-header">
        <div class="form-card-icon"><i class="fas fa-receipt"></i></div>
        <div>
            <p class="form-card-title">Order & Assignment</p>
            <p class="form-card-subtitle">Order, shop, delivery boy and customer mapping</p>
        </div>
    </div>
    <div class="form-card-body">
        <div class="field-group">
            <label class="field-label" for="order_id">Order</label>
            <select name="order_id" id="order_id" class="field-input {{ $errors->has('order_id') ? 'error' : '' }}">
                @foreach($orders as $id => $entry)
                    <option value="{{ $id }}" {{ (string) old('order_id', optional($deliveryTracking)->order_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                @endforeach
            </select>
            @if($errors->has('order_id'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('order_id') }}</p>@endif
        </div>

        <div class="field-group">
            <label class="field-label" for="shop_id">Shop</label>
            <select name="shop_id" id="shop_id" class="field-input">
                @foreach($shops as $id => $entry)
                    <option value="{{ $id }}" {{ (string) old('shop_id', optional($deliveryTracking)->shop_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                @endforeach
            </select>
        </div>

        <div class="field-group">
            <label class="field-label" for="delivery_boy_id">Delivery Boy</label>
            <select name="delivery_boy_id" id="delivery_boy_id" class="field-input">
                @foreach($deliveryBoys as $id => $entry)
                    <option value="{{ $id }}" {{ (string) old('delivery_boy_id', optional($deliveryTracking)->delivery_boy_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                @endforeach
            </select>
        </div>

        <div class="field-group">
            <label class="field-label" for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" class="field-input">
                @foreach($customers as $id => $entry)
                    <option value="{{ $id }}" {{ (string) old('customer_id', optional($deliveryTracking)->customer_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                @endforeach
            </select>
        </div>

        <div class="field-group">
            <label class="field-label" for="customer_address_id">Customer Address</label>
            <select name="customer_address_id" id="customer_address_id" class="field-input">
                <option value="">Please Select</option>
                @foreach($customerAddresses as $address)
                    <option value="{{ $address->id }}" {{ (string) old('customer_address_id', optional($deliveryTracking)->customer_address_id) === (string) $address->id ? 'selected' : '' }}>
                        {{ optional($address->user)->name }} - {{ $address->city ?: '-' }} {{ $address->pincode ? '(' . $address->pincode . ')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="form-card">
    <div class="form-card-header">
        <div class="form-card-icon"><i class="fas fa-map-marker-alt"></i></div>
        <div>
            <p class="form-card-title">Address Details</p>
            <p class="form-card-subtitle">Pickup and customer delivery address</p>
        </div>
    </div>
    <div class="form-card-body">
        <div class="field-group"><label class="field-label" for="pickup_address">Pickup Address</label><textarea name="pickup_address" id="pickup_address" rows="3" class="field-input">{{ old('pickup_address', optional($deliveryTracking)->pickup_address) }}</textarea></div>
        <div class="field-group"><label class="field-label" for="delivery_address">Delivery Address</label><textarea name="delivery_address" id="delivery_address" rows="3" class="field-input">{{ old('delivery_address', optional($deliveryTracking)->delivery_address) }}</textarea></div>
        <div class="field-group"><label class="field-label" for="city">City</label><div class="input-icon-wrap"><i class="fas fa-city icon"></i><input type="text" name="city" id="city" value="{{ old('city', optional($deliveryTracking)->city) }}" class="field-input"></div></div>
        <div class="field-group"><label class="field-label" for="area">Area</label><div class="input-icon-wrap"><i class="fas fa-map icon"></i><input type="text" name="area" id="area" value="{{ old('area', optional($deliveryTracking)->area) }}" class="field-input"></div></div>
        <div class="field-group"><label class="field-label" for="pincode">Pincode</label><div class="input-icon-wrap"><i class="fas fa-map-pin icon"></i><input type="text" name="pincode" id="pincode" value="{{ old('pincode', optional($deliveryTracking)->pincode) }}" class="field-input"></div></div>
        <div class="field-group"><label class="field-label" for="latitude">Latitude</label><input type="text" name="latitude" id="latitude" value="{{ old('latitude', optional($deliveryTracking)->latitude) }}" class="field-input"></div>
        <div class="field-group"><label class="field-label" for="longitude">Longitude</label><input type="text" name="longitude" id="longitude" value="{{ old('longitude', optional($deliveryTracking)->longitude) }}" class="field-input"></div>
    </div>
</div>

<div class="form-card">
    <div class="form-card-header">
        <div class="form-card-icon"><i class="fas fa-wallet"></i></div>
        <div>
            <p class="form-card-title">COD & Status</p>
            <p class="form-card-subtitle">Collection amount and delivery state</p>
        </div>
    </div>
    <div class="form-card-body">
        <div class="field-group">
            <label class="field-label" for="status">Status</label>
            <select name="status" id="status" class="field-input">
                @foreach(\App\Models\DeliveryTracking::STATUSES as $value => $label)
                    <option value="{{ $value }}" {{ old('status', optional($deliveryTracking)->status ?: 'pending') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field-group"><label class="field-label" for="cod_amount">COD Amount</label><input type="number" step="0.01" min="0" name="cod_amount" id="cod_amount" value="{{ old('cod_amount', optional($deliveryTracking)->cod_amount ?? 0) }}" class="field-input"></div>
        <div class="checkbox-grid">
            <label class="role-checkbox-item {{ old('cod_collected', optional($deliveryTracking)->cod_collected) ? 'checked' : '' }}">
                <input type="checkbox" name="cod_collected" value="1" class="role-checkbox" {{ old('cod_collected', optional($deliveryTracking)->cod_collected) ? 'checked' : '' }}>
                <div class="check-icon"></div>
                <span class="checkbox-text">COD Collected</span>
            </label>
        </div>
    </div>
</div>

<div class="form-card">
    <div class="form-card-header">
        <div class="form-card-icon"><i class="fas fa-sticky-note"></i></div>
        <div>
            <p class="form-card-title">Notes</p>
            <p class="form-card-subtitle">Failure reason, delivery note and admin note</p>
        </div>
    </div>
    <div class="form-card-body">
        <div class="field-group"><label class="field-label" for="failure_reason">Failure Reason</label><input type="text" name="failure_reason" id="failure_reason" value="{{ old('failure_reason', optional($deliveryTracking)->failure_reason) }}" class="field-input"></div>
        <div class="field-group"><label class="field-label" for="delivery_note">Delivery Note</label><textarea name="delivery_note" id="delivery_note" rows="3" class="field-input">{{ old('delivery_note', optional($deliveryTracking)->delivery_note) }}</textarea></div>
        <div class="field-group"><label class="field-label" for="admin_note">Admin Note</label><textarea name="admin_note" id="admin_note" rows="3" class="field-input">{{ old('admin_note', optional($deliveryTracking)->admin_note) }}</textarea></div>
    </div>
</div>
