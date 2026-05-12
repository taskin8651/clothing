<div class="form-card">
    <div class="form-card-header"><div class="form-card-icon"><i class="fas fa-receipt"></i></div><div><p class="form-card-title">{{ $returnRequest ? 'Return Request Info' : 'Order & Item' }}</p><p class="form-card-subtitle">Select order item and verify return eligibility</p></div></div>
    <div class="form-card-body">
        <div class="field-group"><label class="field-label">Order <span class="req">*</span></label><select name="order_id" id="order_id" required class="field-input">@foreach($orders as $id => $entry)<option value="{{ $id }}" {{ (string) old('order_id', optional($returnRequest)->order_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>@endforeach</select></div>
        <div class="field-group">
            <label class="field-label">Order Item <span class="req">*</span></label>
            <select name="order_item_id" id="order_item_id" required class="field-input {{ $errors->has('order_item_id') ? 'error' : '' }}" onchange="showEligibility(this)">
                <option value="">Please Select</option>
                @foreach($orderItems as $item)
                    <option value="{{ $item->id }}" data-order="{{ $item->order_id }}" data-eligible="{{ $item->return_eligible ? 1 : 0 }}" data-try="{{ $item->try_cloth_selected ? 1 : 0 }}" data-product="{{ $item->product_name }}" data-total="{{ $item->total }}" {{ (string) old('order_item_id', optional($returnRequest)->order_item_id) === (string) $item->id ? 'selected' : '' }}>
                        {{ optional($item->order)->order_number }} - {{ $item->product_name }} - Rs. {{ number_format($item->total, 2) }}
                    </option>
                @endforeach
            </select>
            @if($errors->has('order_item_id'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('order_item_id') }}</p>@endif
            <p id="eligibilityNote" class="field-hint">Only return eligible items can be selected.</p>
        </div>
        <div class="field-group"><label class="field-label">Customer</label><select name="customer_id" class="field-input">@foreach($customers as $id => $entry)<option value="{{ $id }}" {{ (string) old('customer_id', optional($returnRequest)->customer_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>@endforeach</select></div>
        <div class="field-group"><label class="field-label">Shop</label><select name="shop_id" class="field-input">@foreach($shops as $id => $entry)<option value="{{ $id }}" {{ (string) old('shop_id', optional($returnRequest)->shop_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>@endforeach</select></div>
    </div>
</div>
<div class="form-card"><div class="form-card-header"><div class="form-card-icon"><i class="fas fa-undo-alt"></i></div><div><p class="form-card-title">Return Details</p><p class="form-card-subtitle">Reason, description and refund amount</p></div></div><div class="form-card-body">
    <div class="field-group"><label class="field-label">Reason</label><input name="reason" value="{{ old('reason', optional($returnRequest)->reason) }}" class="field-input"></div>
    <div class="field-group"><label class="field-label">Description</label><textarea name="description" rows="4" class="field-input">{{ old('description', optional($returnRequest)->description) }}</textarea></div>
    <div class="field-group"><label class="field-label">Refund Amount</label><input type="number" step="0.01" min="0" name="refund_amount" value="{{ old('refund_amount', optional($returnRequest)->refund_amount) }}" class="field-input"></div>
</div></div>
<div class="form-card"><div class="form-card-header"><div class="form-card-icon"><i class="fas fa-sticky-note"></i></div><div><p class="form-card-title">{{ $returnRequest ? 'Status & Admin Note' : 'Admin Note' }}</p><p class="form-card-subtitle">Internal handling details</p></div></div><div class="form-card-body">
    @if($returnRequest)
        <div class="field-group"><label class="field-label">Status</label><select name="status" class="field-input">@foreach(\App\Models\ReturnRequest::STATUSES as $value => $label)<option value="{{ $value }}" {{ old('status', $returnRequest->status) === $value ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select></div>
    @endif
    <div class="field-group"><label class="field-label">Admin Note</label><textarea name="admin_note" rows="4" class="field-input">{{ old('admin_note', optional($returnRequest)->admin_note) }}</textarea></div>
</div></div>
