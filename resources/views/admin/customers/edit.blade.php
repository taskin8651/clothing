@extends('layouts.admin')

@section('page-title', 'Edit Customer')

@section('content')

@php
    $colors = ['#4F46E5','#0EA5E9','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6'];
    $color = $colors[$customer->id % count($colors)];
@endphp

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.customers.index') }}" class="admin-back-link">
            &larr; {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Edit Customer</h2>

        <p class="admin-page-subtitle">
            Update customer account and status
        </p>
    </div>

    <div class="identity-card">
        <div class="identity-avatar" style="background: {{ $color }};">
            {{ strtoupper(substr($customer->name, 0, 1)) }}
        </div>

        <div>
            <p class="identity-title">{{ $customer->name }}</p>
            <p class="identity-subtitle">Customer ID #{{ $customer->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.customers.update', $customer->id) }}">
    @method('PUT')
    @csrf

    <div class="admin-form-grid">

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-user-edit"></i>
                </div>

                <div>
                    <p class="form-card-title">Customer Information</p>
                    <p class="form-card-subtitle">Update customer account details</p>
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
                               value="{{ old('name', $customer->name) }}"
                               required
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
                               value="{{ old('mobile', $customer->mobile) }}"
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
                               value="{{ old('email', $customer->display_email) }}"
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
                    <label class="field-label" for="password">
                        Password <span class="field-hint">(optional)</span>
                    </label>

                    <div class="input-icon-wrap has-eye">
                        <i class="fas fa-lock icon"></i>

                        <input type="password"
                               name="password"
                               id="password"
                               placeholder="Leave blank to keep current password"
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
                        <p class="field-hint">Blank rakhne par old password same rahega.</p>
                    @endif
                </div>

                <div class="form-info-box">
                    <p class="meta-label">Customer Info</p>

                    <div class="meta-grid-2">
                        <div>
                            <p class="meta-small-label">Joined</p>
                            <p class="meta-value-strong">
                                {{ optional($customer->created_at)->format('d M Y') ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="meta-small-label">Addresses</p>
                            <p class="meta-value-strong">
                                {{ $customer->addresses->count() }}
                            </p>
                        </div>
                    </div>
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
                    <label class="role-checkbox-item {{ old('status', $customer->status) ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="status"
                               value="1"
                               class="role-checkbox"
                               {{ old('status', $customer->status) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Active Status</span>
                    </label>
                </div>

                <div class="form-info-box">
                    <p>
                        <i class="fas fa-info-circle"></i>
                        Inactive customer order place nahi kar payega.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <div class="form-actions-between">
        <div class="form-actions-left">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Save Customer
            </button>

            <a href="{{ route('admin.customers.index') }}" class="btn-ghost">
                Cancel
            </a>
        </div>

        @can('customer_delete')
            <button type="submit"
                    form="delete-customer-form"
                    class="btn-danger">
                <i class="fas fa-trash-alt"></i>
                Delete Customer
            </button>
        @endcan
    </div>
</form>

@can('customer_delete')
    <form id="delete-customer-form"
          action="{{ route('admin.customers.destroy', $customer->id) }}"
          method="POST"
          onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
        @method('DELETE')
        @csrf
    </form>
@endcan

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
