@extends('layouts.admin')

@section('page-title', 'Show Payment')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.payments.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Payment Details</h2>
        <p class="admin-page-subtitle">Full transaction information and order sync state</p>
    </div>
    <div class="show-actions">
        @can('payment_edit')
            <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn-primary"><i class="fas fa-pencil-alt"></i> Edit Payment</a>
        @endcan
        <a href="{{ route('admin.payments.index') }}" class="btn-outline"><i class="fas fa-list"></i> Back to list</a>
        @can('payment_delete')
            <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn-danger"><i class="fas fa-trash-alt"></i> Delete Payment</button>
            </form>
        @endcan
    </div>
</div>

<div class="show-grid">
    <div>
        <div class="detail-card mb-3">
            <div class="profile-hero">
                <div class="profile-avatar-lg" style="background:#4F46E5;"><i class="fas fa-credit-card"></i></div>
                <p class="profile-title">Payment #{{ $payment->id }}</p>
                <p class="profile-subtitle">{{ optional($payment->order)->order_number ?: 'No order linked' }}</p>
                <span class="status-pill {{ $payment->status === 'paid' ? 'success' : 'warning' }}">{{ \App\Models\Payment::STATUSES[$payment->status] ?? $payment->status }}</span>
                <span class="status-pill success">{{ strtoupper($payment->payment_method ?: '-') }}</span>
            </div>
            <div class="detail-section-pad-sm">
                <div class="d-grid gap-2" style="grid-template-columns:1fr 1fr;">
                    <div class="stat-mini"><p class="stat-mini-label">Amount</p><p class="stat-mini-value">Rs. {{ number_format($payment->amount, 2) }}</p></div>
                    <div class="stat-mini"><p class="stat-mini-label">Paid At</p><p class="stat-mini-value-sm">{{ optional($payment->paid_at)->format('d M Y, H:i') ?: '-' }}</p></div>
                </div>
            </div>
        </div>

        <div class="detail-card detail-card-pad mb-3">
            <p class="quick-title">Quick Actions</p>
            <div class="quick-list">
                @can('payment_edit')
                    <a href="{{ route('admin.payments.edit', $payment->id) }}" class="quick-link primary"><i class="fas fa-pencil-alt"></i> Edit Payment</a>
                @endcan
                @if($payment->receipts()->exists())
                    @can('receipt_show')
                        <a href="{{ route('admin.receipts.show', $payment->receipts()->latest()->first()->id) }}" class="quick-link"><i class="fas fa-receipt"></i> View Receipt</a>
                    @endcan
                @else
                    @can('receipt_create')
                        <form method="POST" action="{{ route('admin.receipts.generateFromPayment', $payment->id) }}">
                            @csrf
                            <button type="submit" class="quick-link" style="width:100%;border:0;text-align:left;"><i class="fas fa-receipt"></i> Generate Receipt</button>
                        </form>
                    @endcan
                @endif
                <a href="{{ route('admin.payments.index') }}" class="quick-link"><i class="fas fa-list"></i> Back to list</a>
            </div>
        </div>

        @can('payment_status_update')
            <div class="detail-card detail-card-pad">
                <p class="quick-title">Status Update Form</p>
                <form method="POST" action="{{ route('admin.payments.updateStatus', $payment->id) }}">
                    @csrf
                    <div class="field-group">
                        <label class="field-label" for="status">Status</label>
                        <select name="status" id="status" class="field-input" required>
                            @foreach(\App\Models\Payment::STATUSES as $value => $label)
                                <option value="{{ $value }}" {{ $payment->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="checkbox-grid">
                        <label class="role-checkbox-item">
                            <input type="checkbox" name="clear_paid_at" value="1" class="role-checkbox">
                            <div class="check-icon"></div>
                            <span class="checkbox-text">Clear Paid At</span>
                        </label>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="admin_note">Admin Note</label>
                        <textarea name="admin_note" id="admin_note" rows="2" class="field-input"></textarea>
                    </div>
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Update Status</button>
                </form>
            </div>
        @endcan
    </div>

    <div>
        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-wallet"></i></div><p class="detail-section-title">Payment Information</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">ID</span><span class="detail-value code-pill">#{{ $payment->id }}</span></div>
                <div class="detail-row"><span class="detail-label">Method</span><span class="detail-value">{{ \App\Models\Payment::METHODS[$payment->payment_method] ?? $payment->payment_method ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Amount</span><span class="detail-value code-pill">Rs. {{ number_format($payment->amount, 2) }}</span></div>
                <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value">{{ \App\Models\Payment::STATUSES[$payment->status] ?? $payment->status }}</span></div>
                <div class="detail-row"><span class="detail-label">Paid At</span><span class="detail-value">{{ optional($payment->paid_at)->format('d M Y, H:i') ?: '-' }}</span></div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-receipt"></i></div><p class="detail-section-title">Order Information</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">Order</span><span class="detail-value">{{ optional($payment->order)->order_number ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Order Status</span><span class="detail-value">{{ optional($payment->order)->order_status ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Order Payment Status</span><span class="detail-value">{{ optional($payment->order)->payment_status ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Order Total</span><span class="detail-value">Rs. {{ optional($payment->order)->total_amount ? number_format($payment->order->total_amount, 2) : '0.00' }}</span></div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-user"></i></div><p class="detail-section-title">Customer & Shop</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">Customer</span><span class="detail-value">{{ optional(optional($payment->order)->customer)->name ?: optional($payment->order)->customer_name ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Shop</span><span class="detail-value">{{ optional(optional($payment->order)->shop)->shop_name ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Delivery Boy</span><span class="detail-value">{{ optional(optional($payment->order)->deliveryBoy)->name ?: '-' }}</span></div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-hashtag"></i></div><p class="detail-section-title">Gateway / Transaction Details</p></div>
            <div class="detail-section-body">
                <div class="detail-row"><span class="detail-label">Gateway</span><span class="detail-value">{{ $payment->payment_gateway ?: '-' }}</span></div>
                <div class="detail-row"><span class="detail-label">Transaction ID</span><span class="detail-value code-pill">{{ $payment->transaction_id ?: '-' }}</span></div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-section-head"><div class="detail-section-icon"><i class="fas fa-code"></i></div><p class="detail-section-title">Gateway Response</p></div>
            <div class="detail-section-body">
                @if($payment->gateway_response)
                    <pre style="white-space:pre-wrap;margin:0;font-size:12px;color:#475569;">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</pre>
                @else
                    <div class="assign-empty">No gateway response</div>
                @endif
            </div>
        </div>
    </div>
</div>

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
