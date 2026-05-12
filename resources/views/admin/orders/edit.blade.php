@extends('layouts.admin')

@section('page-title', 'Edit Order')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">Edit Order</h2>
        <p class="admin-page-subtitle">Update assignment, status, payment and notes</p>
    </div>

    <div class="identity-card">
        <div class="identity-avatar" style="background:#4F46E5;"><i class="fas fa-receipt"></i></div>
        <div>
            <p class="identity-title">{{ $order->order_number }}</p>
            <p class="identity-subtitle">Order ID #{{ $order->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
    @method('PUT')
    @csrf

    <div class="admin-form-grid">
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-user"></i></div>
                <div>
                    <p class="form-card-title">Order Assignment</p>
                    <p class="form-card-subtitle">Customer, address, shop and delivery boy</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="customer_id">Customer</label>
                    <select name="customer_id" id="customer_id" class="field-input">
                        @foreach($customers as $id => $entry)
                            <option value="{{ $id }}" {{ (string) old('customer_id', $order->customer_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-group">
                    <label class="field-label" for="customer_address_id">Address</label>
                    <select name="customer_address_id" id="customer_address_id" class="field-input">
                        <option value="">Please Select</option>
                        @foreach($addresses as $address)
                            <option value="{{ $address->id }}" {{ (string) old('customer_address_id', $order->customer_address_id) === (string) $address->id ? 'selected' : '' }}>
                                {{ optional($address->user)->name }} - {{ $address->city ?: '-' }} {{ $address->pincode ? '(' . $address->pincode . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field-group">
                    <label class="field-label" for="shop_id">Shop</label>
                    <select name="shop_id" id="shop_id" class="field-input">
                        @foreach($shops as $id => $entry)
                            <option value="{{ $id }}" {{ (string) old('shop_id', $order->shop_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-group">
                    <label class="field-label" for="delivery_boy_id">Delivery Boy</label>
                    <select name="delivery_boy_id" id="delivery_boy_id" class="field-input">
                        @foreach($deliveryBoys as $id => $entry)
                            <option value="{{ $id }}" {{ (string) old('delivery_boy_id', $order->delivery_boy_id) === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-sliders-h"></i></div>
                <div>
                    <p class="form-card-title">Status & Payment</p>
                    <p class="form-card-subtitle">Operational and payment state</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="order_status">Order Status</label>
                    <select name="order_status" id="order_status" class="field-input">
                        @foreach(\App\Models\Order::ORDER_STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ old('order_status', $order->order_status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-group">
                    <label class="field-label" for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="field-input">
                        @foreach(\App\Models\Order::PAYMENT_METHODS as $value => $label)
                            <option value="{{ $value }}" {{ old('payment_method', $order->payment_method) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-group">
                    <label class="field-label" for="payment_status">Payment Status</label>
                    <select name="payment_status" id="payment_status" class="field-input">
                        @foreach(\App\Models\Order::PAYMENT_STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ old('payment_status', $order->payment_status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-info-box">
                    <p><strong>Total:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>
                    <p><strong>Return:</strong> {{ $order->return_eligible ? 'Allowed' : 'Not allowed' }}</p>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-sticky-note"></i></div>
                <div>
                    <p class="form-card-title">Notes</p>
                    <p class="form-card-subtitle">Customer and internal notes</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="notes">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="field-input">{{ old('notes', $order->notes) }}</textarea>
                </div>
                <div class="field-group">
                    <label class="field-label" for="admin_note">Admin Note</label>
                    <textarea name="admin_note" id="admin_note" rows="4" class="field-input">{{ old('admin_note', $order->admin_note) }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-list"></i></div>
                <div>
                    <p class="form-card-title">Existing Items</p>
                    <p class="form-card-subtitle">Items are readonly after order creation</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="page-card-table">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Size/Color</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Return</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name ?: optional($item->product)->name }}</td>
                                    <td>{{ $item->size ?: '-' }} / {{ $item->color ?: '-' }}</td>
                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>₹{{ number_format($item->total, 2) }}</td>
                                    <td>{{ $item->return_eligible ? 'Allowed' : 'No' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions-between">
        <div class="form-actions-left">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Order</button>
            <a href="{{ route('admin.orders.index') }}" class="btn-ghost">Cancel</a>
        </div>
        @can('order_delete')
            <button type="submit" form="delete-order-form" class="btn-danger"><i class="fas fa-trash-alt"></i> Delete Order</button>
        @endcan
    </div>
</form>

@can('order_delete')
    <form id="delete-order-form" action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
        @method('DELETE')
        @csrf
    </form>
@endcan

@endsection

