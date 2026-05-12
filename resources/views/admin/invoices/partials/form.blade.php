<div class="form-card">
    <div class="form-card-header"><div class="form-card-icon"><i class="fas fa-file-invoice"></i></div><div><p class="form-card-title">Invoice Reference</p><p class="form-card-subtitle">Order, customer, shop and payment reference</p></div></div>
    <div class="form-card-body">
        <div class="field-group"><label class="field-label">Order</label><select name="order_id" class="field-input">@foreach($orders as $id => $entry)<option value="{{ $id }}" {{ (string) old('order_id', optional($invoice)->order_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>@endforeach</select></div>
        <div class="field-group"><label class="field-label">Customer</label><select name="customer_id" class="field-input">@foreach($customers as $id => $entry)<option value="{{ $id }}" {{ (string) old('customer_id', optional($invoice)->customer_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>@endforeach</select></div>
        <div class="field-group"><label class="field-label">Shop</label><select name="shop_id" class="field-input">@foreach($shops as $id => $entry)<option value="{{ $id }}" {{ (string) old('shop_id', optional($invoice)->shop_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>@endforeach</select></div>
        <div class="field-group"><label class="field-label">Payment</label><select name="payment_id" class="field-input">@foreach($payments as $id => $entry)<option value="{{ $id }}" {{ (string) old('payment_id', optional($invoice)->payment_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>@endforeach</select></div>
        <div class="field-group"><label class="field-label">Invoice Date</label><input type="date" name="invoice_date" value="{{ old('invoice_date', optional(optional($invoice)->invoice_date)->format('Y-m-d')) }}" class="field-input"></div>
        <div class="field-group"><label class="field-label">Due Date</label><input type="date" name="due_date" value="{{ old('due_date', optional(optional($invoice)->due_date)->format('Y-m-d')) }}" class="field-input"></div>
    </div>
</div>
<div class="form-card"><div class="form-card-header"><div class="form-card-icon"><i class="fas fa-user"></i></div><div><p class="form-card-title">Customer Snapshot</p><p class="form-card-subtitle">Saved customer billing/shipping details</p></div></div><div class="form-card-body">
    <div class="field-group"><label class="field-label">Customer Name</label><input name="customer_name" value="{{ old('customer_name', optional($invoice)->customer_name) }}" class="field-input"></div>
    <div class="field-group"><label class="field-label">Customer Mobile</label><input name="customer_mobile" value="{{ old('customer_mobile', optional($invoice)->customer_mobile) }}" class="field-input"></div>
    <div class="field-group"><label class="field-label">Customer Email</label><input type="email" name="customer_email" value="{{ old('customer_email', optional($invoice)->customer_email) }}" class="field-input"></div>
    <div class="field-group"><label class="field-label">Billing Address</label><textarea name="billing_address" rows="3" class="field-input">{{ old('billing_address', optional($invoice)->billing_address) }}</textarea></div>
    <div class="field-group"><label class="field-label">Shipping Address</label><textarea name="shipping_address" rows="3" class="field-input">{{ old('shipping_address', optional($invoice)->shipping_address) }}</textarea></div>
</div></div>
<div class="form-card"><div class="form-card-header"><div class="form-card-icon"><i class="fas fa-store"></i></div><div><p class="form-card-title">Shop Snapshot</p><p class="form-card-subtitle">Saved seller details</p></div></div><div class="form-card-body">
    <div class="field-group"><label class="field-label">Shop Name</label><input name="shop_name" value="{{ old('shop_name', optional($invoice)->shop_name) }}" class="field-input"></div>
    <div class="field-group"><label class="field-label">Shop Mobile</label><input name="shop_mobile" value="{{ old('shop_mobile', optional($invoice)->shop_mobile) }}" class="field-input"></div>
    <div class="field-group"><label class="field-label">Shop Email</label><input type="email" name="shop_email" value="{{ old('shop_email', optional($invoice)->shop_email) }}" class="field-input"></div>
    <div class="field-group"><label class="field-label">Shop Address</label><textarea name="shop_address" rows="3" class="field-input">{{ old('shop_address', optional($invoice)->shop_address) }}</textarea></div>
    <div class="field-group"><label class="field-label">Shop GST Number</label><input name="shop_gst_number" value="{{ old('shop_gst_number', optional($invoice)->shop_gst_number) }}" class="field-input"></div>
</div></div>
<div class="form-card"><div class="form-card-header"><div class="form-card-icon"><i class="fas fa-calculator"></i></div><div><p class="form-card-title">Amounts</p><p class="form-card-subtitle">Totals and due amount</p></div></div><div class="form-card-body">
    @foreach(['subtotal','discount_amount','tax_amount','delivery_charge','round_off','total_amount','paid_amount'] as $field)
        <div class="field-group"><label class="field-label">{{ ucwords(str_replace('_',' ', $field)) }}</label><input type="number" step="0.01" name="{{ $field }}" value="{{ old($field, optional($invoice)->{$field} ?? 0) }}" class="field-input"></div>
    @endforeach
    <div class="form-info-box"><p><strong>Due Amount:</strong> Rs. {{ number_format(max(0, (float) old('total_amount', optional($invoice)->total_amount ?? 0) - (float) old('paid_amount', optional($invoice)->paid_amount ?? 0)), 2) }}</p></div>
</div></div>
<div class="form-card"><div class="form-card-header"><div class="form-card-icon"><i class="fas fa-sliders-h"></i></div><div><p class="form-card-title">Status & Notes</p><p class="form-card-subtitle">Payment, invoice status and notes</p></div></div><div class="form-card-body">
    <div class="field-group"><label class="field-label">Payment Method</label><input name="payment_method" value="{{ old('payment_method', optional($invoice)->payment_method) }}" class="field-input"></div>
    <div class="field-group"><label class="field-label">Payment Status</label><input name="payment_status" value="{{ old('payment_status', optional($invoice)->payment_status) }}" class="field-input"></div>
    <div class="field-group"><label class="field-label">Invoice Status</label><select name="invoice_status" class="field-input">@foreach(\App\Models\Invoice::STATUSES as $value => $label)<option value="{{ $value }}" {{ old('invoice_status', optional($invoice)->invoice_status ?: 'issued') === $value ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select></div>
    <div class="field-group"><label class="field-label">Notes</label><textarea name="notes" rows="3" class="field-input">{{ old('notes', optional($invoice)->notes) }}</textarea></div>
    <div class="field-group"><label class="field-label">Terms</label><textarea name="terms" rows="3" class="field-input">{{ old('terms', optional($invoice)->terms) }}</textarea></div>
    <div class="field-group"><label class="field-label">Admin Note</label><textarea name="admin_note" rows="3" class="field-input">{{ old('admin_note', optional($invoice)->admin_note) }}</textarea></div>
</div></div>
