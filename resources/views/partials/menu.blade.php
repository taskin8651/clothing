<aside id="sidebar">

    {{-- BRAND --}}
    <div class="sidebar-brand">
        <div class="brand-area">
            <div class="brand-icon">
                <i class="fas fa-bolt"></i>
            </div>

            <span class="brand-text">
                {{ trans('panel.site_title') }}
            </span>
        </div>
    </div>

    {{-- USER MINI CARD --}}
    <div class="user-info">
        <div class="user-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div class="user-meta">
            <p class="user-name">{{ auth()->user()->name }}</p>
            <p class="user-role">Administrator</p>
        </div>
    </div>

    {{-- NAV --}}
    <nav class="sidebar-nav">

        <p class="sidebar-section-title nav-label">Main</p>

        {{-- Dashboard --}}
        <a href="{{ route('admin.home') }}"
           data-tooltip="Dashboard"
           class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
            <i class="fas fa-chart-pie nav-icon"></i>
            <span class="nav-label">{{ trans('global.dashboard') }}</span>
        </a>

       {{-- USER MANAGEMENT GROUP --}}
@can('user_management_access')
    @php
        $umActive = request()->is('admin/permissions*')
            || request()->is('admin/roles*')
            || request()->is('admin/users*')
            || request()->is('admin/audit-logs*');
    @endphp

    <div x-data="{ open: {{ $umActive ? 'true' : 'false' }} }">

        <button type="button"
                @click="open = !open"
                data-tooltip="Users"
                class="nav-link nav-group-btn {{ $umActive ? 'active' : '' }}">

            <div class="nav-group-left">
                <i class="fas fa-users nav-icon"></i>
                <span class="nav-label">{{ trans('cruds.userManagement.title') }}</span>
            </div>

            <i class="fas fa-chevron-right chevron"
               :style="open ? 'transform:rotate(90deg)' : ''"></i>
        </button>

        <div class="submenu"
             x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1">

            @can('permission_access')
                <a href="{{ route('admin.permissions.index') }}"
                   class="sub-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                    <i class="fas fa-key"></i>
                    {{ trans('cruds.permission.title') }}
                </a>
            @endcan

            @can('role_access')
                <a href="{{ route('admin.roles.index') }}"
                   class="sub-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt"></i>
                    {{ trans('cruds.role.title') }}
                </a>
            @endcan

            @can('user_access')
                <a href="{{ route('admin.users.index') }}"
                   class="sub-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    {{ trans('cruds.user.title') }}
                </a>
            @endcan

            @can('audit_log_access')
                <a href="{{ route('admin.audit-logs.index') }}"
                   class="sub-link {{ request()->is('admin/audit-logs*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    {{ trans('cruds.auditLog.title') }}
                </a>
            @endcan

        </div>
    </div>
@endcan


{{-- CATALOG MANAGEMENT GROUP --}}
@canany(['shop_access', 'category_access', 'product_access', 'product_variant_access'])
    @php
        $catalogActive = request()->is('admin/shops*')
            || request()->is('admin/categories*')
            || request()->is('admin/products*')
            || request()->is('admin/product-variants*');
    @endphp

    <p class="sidebar-section-title nav-label">Catalog</p>

    <div x-data="{ open: {{ $catalogActive ? 'true' : 'false' }} }">

        <button type="button"
                @click="open = !open"
                data-tooltip="Catalog"
                class="nav-link nav-group-btn {{ $catalogActive ? 'active' : '' }}">

            <div class="nav-group-left">
                <i class="fas fa-store-alt nav-icon"></i>
                <span class="nav-label">Catalog Management</span>
            </div>

            <i class="fas fa-chevron-right chevron"
               :style="open ? 'transform:rotate(90deg)' : ''"></i>
        </button>

        <div class="submenu"
             x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1">

            @can('shop_access')
                <a href="{{ route('admin.shops.index') }}"
                   class="sub-link {{ request()->is('admin/shops*') ? 'active' : '' }}">
                    <i class="fas fa-store"></i>
                    Shops
                </a>
            @endcan

            @can('category_access')
                <a href="{{ route('admin.categories.index') }}"
                   class="sub-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>
                    Categories
                </a>
            @endcan

            @can('product_access')
                <a href="{{ route('admin.products.index') }}"
                   class="sub-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                    <i class="fas fa-tshirt"></i>
                    Products
                </a>
            @endcan

            @can('product_variant_access')
                <a href="{{ route('admin.product-variants.index') }}"
                   class="sub-link {{ request()->is('admin/product-variants*') ? 'active' : '' }}">
                    <i class="fas fa-sliders-h"></i>
                    Product Variants
                </a>
            @endcan

        </div>
    </div>
@endcanany


{{-- CUSTOMER MANAGEMENT GROUP --}}
@canany(['customer_access', 'customer_address_access'])
    @php
        $customerActive = request()->is('admin/customers*')
            || request()->is('admin/customer-addresses*');
    @endphp

    <p class="sidebar-section-title nav-label">Customers</p>

    <div x-data="{ open: {{ $customerActive ? 'true' : 'false' }} }">

        <button type="button"
                @click="open = !open"
                data-tooltip="Customers"
                class="nav-link nav-group-btn {{ $customerActive ? 'active' : '' }}">

            <div class="nav-group-left">
                <i class="fas fa-user-friends nav-icon"></i>
                <span class="nav-label">Customer Management</span>
            </div>

            <i class="fas fa-chevron-right chevron"
               :style="open ? 'transform:rotate(90deg)' : ''"></i>
        </button>

        <div class="submenu"
             x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1">

            @can('customer_access')
                <a href="{{ route('admin.customers.index') }}"
                   class="sub-link {{ request()->is('admin/customers*') && !request()->is('admin/customer-addresses*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    Customers
                </a>
            @endcan

            @can('customer_address_access')
                <a href="{{ route('admin.customer-addresses.index') }}"
                   class="sub-link {{ request()->is('admin/customer-addresses*') ? 'active' : '' }}">
                    <i class="fas fa-map-marker-alt"></i>
                    Customer Addresses
                </a>
            @endcan

        </div>
    </div>
@endcanany


        <div class="nav-divider"></div>

        <p class="sidebar-section-title compact nav-label">Account</p>

        {{-- Change Password --}}
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <a href="{{ route('profile.password.edit') }}"
                   data-tooltip="Password"
                   class="nav-link {{ request()->is('profile/password*') ? 'active' : '' }}">
                    <i class="fas fa-key nav-icon"></i>
                    <span class="nav-label">{{ trans('global.change_password') }}</span>
                </a>
            @endcan
        @endif

        {{-- Settings --}}
        <a href="#"
           data-tooltip="Settings"
           class="nav-link">
            <i class="fas fa-cog nav-icon"></i>
            <span class="nav-label">Settings</span>
        </a>

    </nav>

    {{-- LOGOUT --}}
    <div class="sidebar-footer">
        <a href="#"
           onclick="event.preventDefault(); document.getElementById('logoutform').submit();"
           data-tooltip="Logout"
           class="nav-link logout-link">
            <i class="fas fa-sign-out-alt nav-icon"></i>
            <span class="nav-label">{{ trans('global.logout') }}</span>
        </a>
    </div>

</aside>