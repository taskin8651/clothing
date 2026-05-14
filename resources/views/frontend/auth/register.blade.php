@extends('frontend.layouts.app')

@section('title', 'Customer Register')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        <section class="flow-heading">
            <span>Customer Account</span>
            <h1>Create profile</h1>
            <p>Account banne ke baad Customer role auto assign hoga.</p>
        </section>

        <form action="{{ route('frontend.customer.register.store') }}" method="POST" class="front-form-card customer-auth-card">
            @csrf
            @if($errors->any())
                <div class="front-alert danger">{{ $errors->first() }}</div>
            @endif
            <label>Name
                <input type="text" name="name" value="{{ old('name') }}" required>
            </label>
            <label>Mobile
                <input type="tel" name="mobile" value="{{ old('mobile') }}" required>
            </label>
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Optional">
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <label>Confirm Password
                <input type="password" name="password_confirmation" required>
            </label>
            <button type="submit" class="front-btn primary">Create Account</button>
            <a href="{{ route('frontend.customer.login') }}" class="front-btn ghost">Already have account</a>
        </form>
    </main>
</div>
@endsection
