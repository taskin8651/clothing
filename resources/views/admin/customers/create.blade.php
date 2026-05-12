@extends('layouts.admin')

@section('page-title', 'Add Customer')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.customers.index') }}" class="admin-back-link">
            &larr; {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Add Customer</h2>

        <p class="admin-page-subtitle">
            Create customer account for orders and delivery addresses
        </p>
    </div>
</div>

<form method="POST" action="{{ route('admin.customers.store') }}">
    @csrf

    <div class="admin-form-grid">

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-user-friends"></i>
                </div>

                <div>
                    <p class="form-card-title">Customer Information</p>
                    <p class="form-card-subtitle">Basic customer account details</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="name">
                        Customer Name <span class="req">*</span>
                    </label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-user icon"></i>

                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               required
                               placeholder="Enter customer name"
                               class="field-input {{ $errors->has('name') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('name'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="mobile">Mobile</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-phone icon"></i>

                        <input type="text"
                               name="mobile"
                               id="mobile"
                               value="{{ old('mobile') }}"
                               placeholder="Customer mobile number"
                               class="field-input {{ $errors->has('mobile') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('mobile'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('mobile') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="email">Email</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-envelope icon"></i>

                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email') }}"
                               placeholder="customer@example.com"
                               class="field-input {{ $errors->has('email') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('email'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password</label>

                    <div class="input-icon-wrap has-eye">
                        <i class="fas fa-lock icon"></i>

                        <input type="password"
                               name="password"
                               id="password"
                               placeholder="Create customer password"
                               class="field-input {{ $errors->has('password') ? 'error' : '' }}">

                        <button type="button" class="eye-toggle" onclick="togglePass('password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    @if($errors->has('password'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('password') }}
                        </p>
                    @else
                        <p class="field-hint">Optional. Customer login ke liye password.</p>
                    @endif
                </div>

            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>

                <div>
                    <p class="form-card-title">Customer Settings</p>
                    <p class="form-card-subtitle">Account status and access</p>
                </div>
            </div>

            <div class="form-card-body">
                <div class="checkbox-grid">
                    <label class="role-checkbox-item {{ old('status', 1) ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="status"
                               value="1"
                               class="role-checkbox"
                               {{ old('status', 1) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Active Status</span>
                    </label>
                </div>

                <div class="form-info-box">
                    <p>
                        <i class="fas fa-info-circle"></i>
                        Active customers can place orders and manage delivery addresses.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="fas fa-check"></i>
            Save Customer
        </button>

        <a href="{{ route('admin.customers.index') }}" class="btn-ghost">
            Cancel
        </a>
    </div>

</form>

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
