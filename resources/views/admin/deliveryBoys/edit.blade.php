@extends('layouts.admin')

@section('page-title', 'Edit Delivery Boy')

@section('content')

@php
    $colors = ['#4F46E5','#0EA5E9','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6'];
    $color = $colors[$deliveryBoy->id % count($colors)];
@endphp

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.delivery-boys.index') }}" class="admin-back-link">
            &larr; {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Edit Delivery Boy</h2>
        <p class="admin-page-subtitle">Update delivery staff profile, documents and status</p>
    </div>

    <div class="identity-card">
        @if($deliveryBoy->profile_image)
            <img src="{{ $deliveryBoy->profile_image['url'] }}" alt="{{ $deliveryBoy->name }}" class="identity-avatar" style="object-fit:cover;">
        @else
            <div class="identity-avatar" style="background: {{ $color }};">
                {{ strtoupper(substr($deliveryBoy->name, 0, 1)) }}
            </div>
        @endif

        <div>
            <p class="identity-title">{{ $deliveryBoy->name }}</p>
            <p class="identity-subtitle">Delivery Boy ID #{{ $deliveryBoy->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.delivery-boys.update', $deliveryBoy->id) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf

    <div class="admin-form-grid">
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-user-edit"></i></div>
                <div>
                    <p class="form-card-title">Delivery Boy Information</p>
                    <p class="form-card-subtitle">Update login and contact details</p>
                </div>
            </div>

            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="name">Name <span class="req">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-user icon"></i>
                        <input type="text" name="name" id="name" value="{{ old('name', $deliveryBoy->name) }}" required
                               class="field-input {{ $errors->has('name') ? 'error' : '' }}">
                    </div>
                    @if($errors->has('name'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="email">Email</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" name="email" id="email" value="{{ old('email', $deliveryBoy->email) }}"
                               class="field-input {{ $errors->has('email') ? 'error' : '' }}">
                    </div>
                    @if($errors->has('email'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="mobile">Mobile</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-phone icon"></i>
                        <input type="text" name="mobile" id="mobile" value="{{ old('mobile', $deliveryBoy->mobile) }}"
                               class="field-input {{ $errors->has('mobile') ? 'error' : '' }}">
                    </div>
                    @if($errors->has('mobile'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('mobile') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password <span class="field-hint">(optional)</span></label>
                    <div class="input-icon-wrap has-eye">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password" id="password" placeholder="Leave blank to keep current password"
                               class="field-input {{ $errors->has('password') ? 'error' : '' }}">
                        <button type="button" class="eye-toggle" onclick="togglePass('password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @if($errors->has('password'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('password') }}</p>
                    @else
                        <p class="field-hint">Blank rakhne par old password same rahega.</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="profile_image">Profile Image</label>

                    @if($deliveryBoy->profile_image)
                        <div style="margin-bottom:12px;">
                            <img src="{{ $deliveryBoy->profile_image['url'] }}" alt="{{ $deliveryBoy->name }}"
                                 style="width:150px;height:125px;object-fit:cover;border-radius:16px;border:1px solid #E2E8F0;">
                        </div>

                        <label class="role-checkbox-item {{ old('remove_profile_image') ? 'checked' : '' }}" style="margin-bottom:12px;">
                            <input type="checkbox" name="remove_profile_image" value="1" class="role-checkbox" {{ old('remove_profile_image') ? 'checked' : '' }}>
                            <div class="check-icon"></div>
                            <span class="checkbox-text">Remove Profile Image</span>
                        </label>
                    @endif

                    <input type="file" name="profile_image" id="profile_image" accept="image/*"
                           class="field-input {{ $errors->has('profile_image') ? 'error' : '' }}"
                           onchange="previewSingleImage(this, 'profileImagePreview')">
                    @if($errors->has('profile_image'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('profile_image') }}</p>
                    @else
                        <p class="field-hint">Upload new image to replace existing one.</p>
                    @endif
                    <div id="profileImagePreview" style="margin-top:12px;"></div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <p class="form-card-title">Address Details</p>
                    <p class="form-card-subtitle">Delivery location and service area</p>
                </div>
            </div>

            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="address">Address</label>
                    <textarea name="address" id="address" rows="4"
                              class="field-input {{ $errors->has('address') ? 'error' : '' }}">{{ old('address', $deliveryBoy->address) }}</textarea>
                </div>

                <div class="field-group">
                    <label class="field-label" for="city">City</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-city icon"></i>
                        <input type="text" name="city" id="city" value="{{ old('city', $deliveryBoy->city) }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="area">Area</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-map icon"></i>
                        <input type="text" name="area" id="area" value="{{ old('area', $deliveryBoy->area) }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="pincode">Pincode</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-map-pin icon"></i>
                        <input type="text" name="pincode" id="pincode" value="{{ old('pincode', $deliveryBoy->pincode) }}" class="field-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-id-card"></i></div>
                <div>
                    <p class="form-card-title">Vehicle & ID Proof</p>
                    <p class="form-card-subtitle">Vehicle and verification documents</p>
                </div>
            </div>

            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="vehicle_type">Vehicle Type</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-motorcycle icon"></i>
                        <input type="text" name="vehicle_type" id="vehicle_type" value="{{ old('vehicle_type', $deliveryBoy->vehicle_type) }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="vehicle_number">Vehicle Number</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-hashtag icon"></i>
                        <input type="text" name="vehicle_number" id="vehicle_number" value="{{ old('vehicle_number', $deliveryBoy->vehicle_number) }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="id_proof_type">ID Proof Type</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-id-badge icon"></i>
                        <input type="text" name="id_proof_type" id="id_proof_type" value="{{ old('id_proof_type', $deliveryBoy->id_proof_type) }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="id_proof_image">ID Proof Image</label>

                    @if($deliveryBoy->id_proof_image)
                        <div style="margin-bottom:12px;">
                            <img src="{{ $deliveryBoy->id_proof_image['url'] }}" alt="ID Proof"
                                 style="width:150px;height:125px;object-fit:cover;border-radius:16px;border:1px solid #E2E8F0;">
                        </div>

                        <label class="role-checkbox-item {{ old('remove_id_proof_image') ? 'checked' : '' }}" style="margin-bottom:12px;">
                            <input type="checkbox" name="remove_id_proof_image" value="1" class="role-checkbox" {{ old('remove_id_proof_image') ? 'checked' : '' }}>
                            <div class="check-icon"></div>
                            <span class="checkbox-text">Remove ID Proof Image</span>
                        </label>
                    @endif

                    <input type="file" name="id_proof_image" id="id_proof_image" accept="image/*"
                           class="field-input {{ $errors->has('id_proof_image') ? 'error' : '' }}"
                           onchange="previewSingleImage(this, 'idProofImagePreview')">
                    @if($errors->has('id_proof_image'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('id_proof_image') }}</p>
                    @else
                        <p class="field-hint">Upload new image to replace existing one.</p>
                    @endif
                    <div id="idProofImagePreview" style="margin-top:12px;"></div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-sliders-h"></i></div>
                <div>
                    <p class="form-card-title">Delivery Boy Settings</p>
                    <p class="form-card-subtitle">Account availability</p>
                </div>
            </div>

            <div class="form-card-body">
                <div class="checkbox-grid">
                    <label class="role-checkbox-item {{ old('status', $deliveryBoy->status) ? 'checked' : '' }}">
                        <input type="checkbox" name="status" value="1" class="role-checkbox" {{ old('status', $deliveryBoy->status) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Active Status</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions-between">
        <div class="form-actions-left">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Save Delivery Boy
            </button>

            <a href="{{ route('admin.delivery-boys.index') }}" class="btn-ghost">Cancel</a>
        </div>

        @can('delivery_boy_delete')
            <button type="submit" form="delete-delivery-boy-form" class="btn-danger">
                <i class="fas fa-trash-alt"></i>
                Delete Delivery Boy
            </button>
        @endcan
    </div>
</form>

@can('delivery_boy_delete')
    <form id="delete-delivery-boy-form"
          action="{{ route('admin.delivery-boys.destroy', $deliveryBoy->id) }}"
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
function previewSingleImage(input, targetId) {
    const target = document.getElementById(targetId);
    target.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            target.innerHTML = `
                <img src="${e.target.result}"
                     style="width:150px;height:125px;object-fit:cover;border-radius:16px;border:1px solid #E2E8F0;">
            `;
        };

        reader.readAsDataURL(input.files[0]);
    }
}

document.querySelectorAll('.role-checkbox-item input[type="checkbox"]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        const label = this.closest('.role-checkbox-item');
        this.checked ? label.classList.add('checked') : label.classList.remove('checked');
    });
});
</script>
@endsection
