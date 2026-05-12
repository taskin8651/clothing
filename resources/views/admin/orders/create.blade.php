@extends('layouts.admin')

@section('page-title', 'Add Order')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Add Order</h2>
        <p class="admin-page-subtitle">Create order, assign shop by pincode, and reduce stock</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.orders.store') }}">
    @csrf

    <div class="admin-form-grid">
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-user"></i></div>
                <div>
                    <p class="form-card-title">Customer & Address</p>
                    <p class="form-card-subtitle">Customer, delivery address and assignments</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="customer_id">Customer <span class="req">*</span></label>
                    <select name="customer_id" id="customer_id" required class="field-input {{ $errors->has('customer_id') ? 'error' : '' }}">
                        @foreach($customers as $id => $entry)
                            <option value="{{ $id }}" {{ (string) old('customer_id') === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('customer_id'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('customer_id') }}</p>@endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="customer_address_id">Customer Address <span class="req">*</span></label>
                    <select name="customer_address_id" id="customer_address_id" required class="field-input {{ $errors->has('customer_address_id') ? 'error' : '' }}">
                        <option value="">Please Select</option>
                        @foreach($addresses as $address)
                            <option value="{{ $address->id }}" data-customer="{{ $address->user_id }}" {{ (string) old('customer_address_id') === (string) $address->id ? 'selected' : '' }}>
                                {{ optional($address->user)->name }} - {{ $address->city ?: '-' }} {{ $address->pincode ? '(' . $address->pincode . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('customer_address_id'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('customer_address_id') }}</p>@endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="shop_id">Shop</label>
                    <select name="shop_id" id="shop_id" class="field-input">
                        @foreach($shops as $id => $entry)
                            <option value="{{ $id }}" {{ (string) old('shop_id') === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    <p class="field-hint">Blank rakhenge to pincode se active shop auto assign hoga.</p>
                </div>

                <div class="field-group">
                    <label class="field-label" for="delivery_boy_id">Delivery Boy</label>
                    <select name="delivery_boy_id" id="delivery_boy_id" class="field-input">
                        @foreach($deliveryBoys as $id => $entry)
                            <option value="{{ $id }}" {{ (string) old('delivery_boy_id') === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-tshirt"></i></div>
                <div>
                    <p class="form-card-title">Order Items</p>
                    <p class="form-card-subtitle">Add products, variants and quantities</p>
                </div>
            </div>
            <div class="form-card-body">
                <div id="orderItems"></div>
                @if($errors->has('items'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('items') }}</p>@endif
                <button type="button" class="btn-outline btn-outline-edit" onclick="addItemRow()">
                    <i class="fas fa-plus"></i>
                    Add Item
                </button>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-credit-card"></i></div>
                <div>
                    <p class="form-card-title">Payment & Charges</p>
                    <p class="form-card-subtitle">Payment method and final total</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="payment_method">Payment Method <span class="req">*</span></label>
                    <select name="payment_method" id="payment_method" required class="field-input">
                        @foreach(\App\Models\Order::PAYMENT_METHODS as $value => $label)
                            <option value="{{ $value }}" {{ old('payment_method', 'cod') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-group">
                    <label class="field-label" for="payment_status">Payment Status</label>
                    <select name="payment_status" id="payment_status" class="field-input">
                        @foreach(\App\Models\Order::PAYMENT_STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ old('payment_status', 'pending') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-group"><label class="field-label" for="discount_amount">Discount Amount</label><input type="number" step="0.01" min="0" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', 0) }}" class="field-input calc-input"></div>
                <div class="field-group"><label class="field-label" for="delivery_charge">Delivery Charge</label><input type="number" step="0.01" min="0" name="delivery_charge" id="delivery_charge" value="{{ old('delivery_charge', 0) }}" class="field-input calc-input"></div>
                <div class="field-group"><label class="field-label" for="tax_amount">Tax Amount</label><input type="number" step="0.01" min="0" name="tax_amount" id="tax_amount" value="{{ old('tax_amount', 0) }}" class="field-input calc-input"></div>
                <div class="form-info-box">
                    <p><strong>Subtotal:</strong> ₹<span id="subtotalText">0.00</span></p>
                    <p><strong>Total:</strong> ₹<span id="totalText">0.00</span></p>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-clipboard-check"></i></div>
                <div>
                    <p class="form-card-title">Order Rules & Notes</p>
                    <p class="form-card-subtitle">Try Cloth return rule and notes</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="checkbox-grid">
                    <label class="role-checkbox-item {{ old('try_cloth_selected') ? 'checked' : '' }}">
                        <input type="checkbox" name="try_cloth_selected" value="1" class="role-checkbox" {{ old('try_cloth_selected') ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Try Cloth Selected</span>
                    </label>
                </div>
                <div class="form-info-box"><p><i class="fas fa-info-circle"></i> If Try Cloth is selected, return will be disabled.</p></div>
                <div class="field-group"><label class="field-label" for="notes">Notes</label><textarea name="notes" id="notes" rows="3" class="field-input">{{ old('notes') }}</textarea></div>
                <div class="field-group"><label class="field-label" for="admin_note">Admin Note</label><textarea name="admin_note" id="admin_note" rows="3" class="field-input">{{ old('admin_note') }}</textarea></div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary"><i class="fas fa-check"></i> Save Order</button>
        <a href="{{ route('admin.orders.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>

<template id="itemRowTemplate">
    <div class="order-item-row" style="border:1px solid #E2E8F0;border-radius:16px;padding:14px;margin-bottom:12px;">
        <div class="d-grid gap-2" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));">
            <div class="field-group">
                <label class="field-label">Product</label>
                <select class="field-input item-product" name="items[__INDEX__][product_id]" onchange="onProductChange(this)">
                    <option value="">Please Select</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->discount_price ?: $product->price }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Variant</label>
                <select class="field-input item-variant" name="items[__INDEX__][product_variant_id]" onchange="onVariantChange(this)">
                    <option value="">No Variant</option>
                </select>
            </div>
            <div class="field-group"><label class="field-label">Quantity</label><input type="number" min="1" value="1" name="items[__INDEX__][quantity]" class="field-input item-qty" oninput="calculateTotals()"></div>
            <div class="field-group"><label class="field-label">Price</label><input type="number" step="0.01" min="0" name="items[__INDEX__][price]" class="field-input item-price" oninput="calculateTotals()"></div>
            <div class="field-group"><label class="field-label">Row Total</label><input type="text" class="field-input item-total" readonly value="0.00"></div>
        </div>
        <button type="button" class="btn-outline btn-outline-danger" onclick="removeItemRow(this)"><i class="fas fa-trash"></i> Remove</button>
    </div>
</template>

@endsection

@section('scripts')
@parent
<script>
const variantsByProduct = @json($variantsByProduct);
let itemIndex = 0;

function addItemRow() {
    const html = document.getElementById('itemRowTemplate').innerHTML.replaceAll('__INDEX__', itemIndex);
    document.getElementById('orderItems').insertAdjacentHTML('beforeend', html);
    itemIndex++;
    calculateTotals();
}

function removeItemRow(button) {
    button.closest('.order-item-row').remove();
    calculateTotals();
}

function onProductChange(select) {
    const row = select.closest('.order-item-row');
    const productId = select.value;
    const price = select.options[select.selectedIndex]?.dataset.price || 0;
    const variantSelect = row.querySelector('.item-variant');
    variantSelect.innerHTML = '<option value="">No Variant</option>';

    (variantsByProduct[productId] || []).forEach(function (variant) {
        const option = document.createElement('option');
        option.value = variant.id;
        option.textContent = variant.label;
        option.dataset.price = variant.price || 0;
        variantSelect.appendChild(option);
    });

    row.querySelector('.item-price').value = price;
    calculateTotals();
}

function onVariantChange(select) {
    const price = select.options[select.selectedIndex]?.dataset.price;
    if (price) {
        select.closest('.order-item-row').querySelector('.item-price').value = price;
    }
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.order-item-row').forEach(function (row) {
        const qty = parseFloat(row.querySelector('.item-qty').value || 0);
        const price = parseFloat(row.querySelector('.item-price').value || 0);
        const total = qty * price;
        row.querySelector('.item-total').value = total.toFixed(2);
        subtotal += total;
    });

    const discount = parseFloat(document.getElementById('discount_amount').value || 0);
    const delivery = parseFloat(document.getElementById('delivery_charge').value || 0);
    const tax = parseFloat(document.getElementById('tax_amount').value || 0);
    document.getElementById('subtotalText').textContent = subtotal.toFixed(2);
    document.getElementById('totalText').textContent = Math.max(0, subtotal - discount + delivery + tax).toFixed(2);
}

document.querySelectorAll('.calc-input').forEach(input => input.addEventListener('input', calculateTotals));
document.querySelectorAll('.role-checkbox-item input[type="checkbox"]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        const label = this.closest('.role-checkbox-item');
        this.checked ? label.classList.add('checked') : label.classList.remove('checked');
    });
});
addItemRow();
</script>
@endsection

