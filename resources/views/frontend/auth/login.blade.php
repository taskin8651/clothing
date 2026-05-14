@extends('frontend.layouts.app')

@section('title', 'Customer Login')

@section('content')
<div class="zilo-mobile-page">
    @include('frontend.partials.topbar')

    <main class="front-flow-page">
        <section class="flow-heading">
            <span>Customer Login</span>
            <h1>Welcome back</h1>
            <p>Mobile ya email se login karke saved address, orders aur returns manage karein.</p>
        </section>

        <form action="{{ route('frontend.customer.login.store') }}" method="POST" class="front-form-card customer-auth-card">
            @csrf
            @if($errors->any())
                <div class="front-alert danger">{{ $errors->first() }}</div>
            @endif
            <label>Mobile or Email
                <input type="text" name="login" value="{{ old('login') }}" required>
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <label class="inline-check">
                <input type="checkbox" name="remember" value="1">
                <span>Remember me</span>
            </label>
            <button type="submit" class="front-btn primary">Login</button>
            <a href="{{ route('frontend.customer.register') }}" class="front-btn ghost">Create Account</a>
        </form>
    </main>
</div>
@endsection
