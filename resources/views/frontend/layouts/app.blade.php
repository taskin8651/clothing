<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Zilo Style Clothing')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/frontend.css') }}">
    @yield('styles')
</head>
<body>
    @yield('content')

    <nav class="mobile-bottom-nav" aria-label="Mobile navigation">
        <a href="#home" class="active logo-link"><strong>ZILO</strong><span>Home</span></a>
        <a href="#shop"><i class="fas fa-store"></i><span>Shop</span></a>
        <a href="#search"><i class="fas fa-magnifying-glass"></i><span>Search</span></a>
        <a href="{{ route('login') }}"><i class="fas fa-user"></i><span>Profile</span></a>
        <a href="#bag"><i class="fas fa-bag-shopping"></i><span>Bag</span></a>
    </nav>

    <script src="{{ asset('assets/frontend/js/frontend.js') }}"></script>
    @yield('scripts')
</body>
</html>
