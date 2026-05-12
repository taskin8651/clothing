@extends('layouts.admin')

@section('page-title', 'Edit Payment')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.payments.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Edit Payment</h2>
        <p class="admin-page-subtitle">Update payment transaction and sync order payment status</p>
    </div>
    <div class="identity-card">
        <div class="identity-avatar" style="background:#4F46E5;"><i class="fas fa-credit-card"></i></div>
        <div>
            <p class="identity-title">Payment #{{ $payment->id }}</p>
            <p class="identity-subtitle">{{ optional($payment->order)->order_number ?: 'No order' }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.payments.update', $payment->id) }}">
    @method('PUT')
    @csrf

    <div class="admin-form-grid">
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-receipt"></i></div>
                <div>
                    <p class="form-card-title">Payment Information</p>
                    <p class="form-card-subtitle">Order and transaction identifiers</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="order_id">Order <span class="req">*</span></label>
                    <select name="order_id" id="order_id" required class="field-input {{ $errors->has('order_id') ? 'error' : '' }}">
                        @foreach($orders as $id => $entry)
                            <option value="{{ $id }}" {{ (string) old('order_id', $payment->order_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field-group">
                    <label class="field-label" for="payment_method">Payment Method <span class="req">*</span></label>
                    <select name="payment_method" id="payment_method" required class="field-input">
                        @foreach(\App\Models\Payment::METHODS as $value => $label)
                            <option value="{{ $value }}" {{ old('payment_method', $payment->payment_method) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field-group">
                    <label class="field-label" for="payment_gateway">Payment Gateway</label>
                    <input type="text" name="payment_gateway" id="payment_gateway" value="{{ old('payment_gateway', $payment->payment_gateway) }}" class="field-input">
                </div>

                <div class="field-group">
                    <label class="field-label" for="transaction_id">Transaction ID</label>
                    <input type="text" name="transaction_id" id="transaction_id" value="{{ old('transaction_id', $payment->transaction_id) }}" class="field-input">
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-wallet"></i></div>
                <div>
                    <p class="form-card-title">Amount & Status</p>
                    <p class="form-card-subtitle">Payment amount, status and paid timestamp</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="amount">Amount <span class="req">*</span></label>
                    <input type="number" step="0.01" min="0" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}" required class="field-input {{ $errors->has('amount') ? 'error' : '' }}">
                </div>

                <div class="field-group">
                    <label class="field-label" for="status">Status <span class="req">*</span></label>
                    <select name="status" id="status" required class="field-input">
                        @foreach(\App\Models\Payment::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $payment->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field-group">
                    <label class="field-label" for="paid_at">Paid At</label>
                    <input type="datetime-local" name="paid_at" id="paid_at" value="{{ old('paid_at', optional($payment->paid_at)->format('Y-m-d\TH:i')) }}" class="field-input">
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-code"></i></div>
                <div>
                    <p class="form-card-title">Gateway Response</p>
                    <p class="form-card-subtitle">Editable JSON or raw gateway response</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="gateway_response">Gateway Response</label>
                    <textarea name="gateway_response" id="gateway_response" rows="10" class="field-input">{{ old('gateway_response', $payment->gateway_response ? json_encode($payment->gateway_response, JSON_PRETTY_PRINT) : '') }}</textarea>
                    <p class="field-hint">Valid JSON will be stored as JSON. Plain text will be stored under raw.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions-between">
        <div class="form-actions-left">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Payment</button>
            <a href="{{ route('admin.payments.index') }}" class="btn-ghost">Cancel</a>
        </div>
        @can('payment_delete')
            <button type="submit" form="delete-payment-form" class="btn-danger"><i class="fas fa-trash-alt"></i> Delete Payment</button>
        @endcan
    </div>
</form>

@can('payment_delete')
    <form id="delete-payment-form" action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
        @method('DELETE')
        @csrf
    </form>
@endcan

@endsection
