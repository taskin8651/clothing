@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        <section class="flow-heading">
            <span>Checkout</span>
            <h1>Choose how you buy</h1>
            <p>Try Cloth select karne par product return nahi hoga. Try Cloth skip karne par return available rahega.</p>
        </section>

        @if($errors->any())
            <div class="front-alert danger">Please required details sahi fill karein.</div>
        @endif

        <form action="{{ route('frontend.checkout.store') }}" method="POST" class="checkout-form">
            @csrf

            <section class="try-rule-card">
                <label class="try-toggle">
                    <input type="checkbox" name="try_cloth_selected" value="1" data-try-toggle {{ old('try_cloth_selected') ? 'checked' : '' }}>
                    <span>
                        <strong>Try Cloth at delivery</strong>
                        <small>Rider wait karega, fit check kar sakte ho.</small>
                    </span>
                </label>
                <div class="try-rule-message" data-try-message>
                    Normal buy selected: product return eligible rahega.
                </div>
            </section>

            <section class="front-form-card">
                <h2>Delivery Details</h2>
                @if(auth()->check() && $addresses->count())
                    <label>Saved address
                        <select name="customer_address_id" data-address-select>
                            <option value="">Use new address</option>
                            @foreach($addresses as $address)
                                <option value="{{ $address->id }}" {{ old('customer_address_id', optional($defaultAddress)->id) == $address->id ? 'selected' : '' }}>
                                    {{ $address->name }} - {{ $address->area }}, {{ $address->pincode }} {{ $address->is_default ? '(Default)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                @endif
                <label>Name
                    <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? optional($defaultAddress)->name) }}" required>
                    @error('customer_name') <small>{{ $message }}</small> @enderror
                </label>
                <label>Mobile
                    <input type="tel" name="customer_mobile" value="{{ old('customer_mobile', auth()->user()->mobile ?? optional($defaultAddress)->mobile) }}" required>
                    @error('customer_mobile') <small>{{ $message }}</small> @enderror
                </label>
                <label>Address
                    <textarea name="delivery_address" rows="3" required>{{ old('delivery_address', optional($defaultAddress)->address) }}</textarea>
                    @error('delivery_address') <small>{{ $message }}</small> @enderror
                </label>
                <div class="form-grid-2">
                    <label>City
                        <input type="text" name="city" value="{{ old('city', optional($defaultAddress)->city ?: 'Mumbai') }}" required>
                    </label>
                    <label>Pincode
                        <input type="text" name="pincode" value="{{ old('pincode', optional($defaultAddress)->pincode ?: '400001') }}" required>
                    </label>
                </div>
                <label>Area
                    <input type="text" name="area" value="{{ old('area', optional($defaultAddress)->area ?: 'Fort') }}">
                </label>
                <label>Notes
                    <textarea name="notes" rows="2">{{ old('notes') }}</textarea>
                </label>
            </section>

            <section class="front-form-card">
                <h2>Payment</h2>
                <div class="payment-options">
                    <label>
                        <input type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                        <span><i class="fas fa-money-bill-wave"></i> COD</span>
                    </label>
                    <label>
                        <input type="radio" name="payment_method" value="online" {{ old('payment_method') === 'online' ? 'checked' : '' }}>
                        <span><i class="fas fa-credit-card"></i> Online</span>
                    </label>
                </div>
            </section>

            <section class="checkout-summary-card">
                @foreach($products as $product)
                    <div>
                        <span>{{ $product->name }} x {{ $product->cart_quantity }}</span>
                        <strong>Rs. {{ number_format($product->cart_total, 0) }}</strong>
                    </div>
                @endforeach
                <div><span>Subtotal</span><strong>Rs. {{ number_format($subtotal, 0) }}</strong></div>
                <div><span>Delivery</span><strong>Rs. {{ number_format($deliveryCharge, 0) }}</strong></div>
                <div class="summary-total"><span>Total</span><strong>Rs. {{ number_format($total, 0) }}</strong></div>
                <button type="submit" class="front-btn primary">Place Order</button>
            </section>
        </form>
    </main>
</div>
@endsection
