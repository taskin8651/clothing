@extends('frontend.layouts.app')

@section('title', 'Delivery Boy Panel')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        @if(session('message'))
            <div class="front-alert">{{ session('message') }}</div>
        @endif

        @if($errors->any())
            <div class="front-alert danger">{{ $errors->first() }}</div>
        @endif

        <section class="flow-heading">
            <span>Delivery Panel</span>
            <h1>Assigned deliveries</h1>
            <p>Delivery boy mobile number se active deliveries dekhein aur pickup/delivered status update karein.</p>
        </section>

        <form action="{{ route('frontend.delivery-boy.index') }}" method="GET" class="front-form-card order-lookup-form">
            <h2>Delivery Boy Login</h2>
            <label>Mobile number
                <input type="tel" name="mobile" value="{{ request('mobile') }}" placeholder="Delivery boy mobile">
            </label>
            <button type="submit" class="front-btn primary">Open Panel</button>
        </form>

        @if(request('mobile') && ! $deliveryBoy)
            <div class="empty-bag-state">
                <i class="fas fa-user-slash"></i>
                <h2>No rider found</h2>
                <p>Admin panel me delivery boy active hai ya nahi check karein.</p>
            </div>
        @endif

        @if($deliveryBoy)
            <section class="delivery-boy-profile">
                <div>
                    <span>Rider</span>
                    <h2>{{ $deliveryBoy->name }}</h2>
                    <p>{{ $deliveryBoy->vehicle_type ?: 'Vehicle' }} {{ $deliveryBoy->vehicle_number ? '| ' . $deliveryBoy->vehicle_number : '' }}</p>
                </div>
                <strong>{{ $trackings->count() }} Active</strong>
            </section>

            <section class="delivery-task-list">
                @forelse($trackings as $tracking)
                    <article class="delivery-task-card">
                        <div class="task-head">
                            <span>{{ $tracking->tracking_number }}</span>
                            <strong>{{ \App\Models\DeliveryTracking::STATUSES[$tracking->status] ?? ucfirst($tracking->status) }}</strong>
                        </div>
                        <h2>{{ optional($tracking->order)->order_number ?: 'Manual Delivery' }}</h2>
                        <p>{{ $tracking->delivery_address }}</p>
                        <small>{{ $tracking->area }}, {{ $tracking->city }} - {{ $tracking->pincode }}</small>

                        <div class="delivery-action-grid">
                            @foreach(['pickup_pending' => 'Pickup', 'picked_up' => 'Picked', 'out_for_delivery' => 'Out', 'delivered' => 'Delivered'] as $status => $label)
                                <form action="{{ route('frontend.delivery-boy.status', $tracking) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="delivery_boy_id" value="{{ $deliveryBoy->id }}">
                                    <input type="hidden" name="status" value="{{ $status }}">
                                    <button type="submit">{{ $label }}</button>
                                </form>
                            @endforeach
                        </div>

                        <form action="{{ route('frontend.delivery-boy.status', $tracking) }}" method="POST" class="failed-delivery-form">
                            @csrf
                            <input type="hidden" name="delivery_boy_id" value="{{ $deliveryBoy->id }}">
                            <input type="hidden" name="status" value="failed_delivery">
                            <input type="text" name="failure_reason" placeholder="Failed delivery reason">
                            <button type="submit">Mark Failed</button>
                        </form>

                        @if($tracking->cod_amount > 0)
                            <form action="{{ route('frontend.delivery-boy.cod', $tracking) }}" method="POST" class="cod-action-form">
                                @csrf
                                <input type="hidden" name="delivery_boy_id" value="{{ $deliveryBoy->id }}">
                                <button type="submit" {{ $tracking->cod_collected ? 'disabled' : '' }}>
                                    {{ $tracking->cod_collected ? 'COD Collected' : 'Collect COD Rs. ' . number_format((float) $tracking->cod_amount, 0) }}
                                </button>
                            </form>
                        @endif
                    </article>
                @empty
                    <div class="empty-bag-state">
                        <i class="fas fa-route"></i>
                        <h2>No active delivery</h2>
                        <p>Assigned deliveries yahan show hongi.</p>
                    </div>
                @endforelse
            </section>
        @endif
    </main>
</div>
@endsection
