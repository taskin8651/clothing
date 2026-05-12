@extends('layouts.admin')

@section('page-title', 'Add Delivery Boy')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.delivery-boys.index') }}" class="admin-back-link">
            &larr; {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Add Delivery Boy</h2>
        <p class="admin-page-subtitle">Create delivery staff profile with vehicle and ID proof</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.delivery-boys.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="admin-form-grid">
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-user"></i></div>
                <div>
                    <p class="form-card-title">Delivery Boy Information</p>
                    <p class="form-card-subtitle">Basic login and contact details</p>
                </div>
            </div>

            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="name">Name <span class="req">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-user icon"></i>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
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
                        <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}"
                               class="field-input {{ $errors->has('mobile') ? 'error' : '' }}">
                    </div>
                    @if($errors->has('mobile'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('mobile') }}</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <div class="input-icon-wrap has-eye">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password" id="password"
                               class="field-input {{ $errors->has('password') ? 'error' : '' }}">
                        <button type="button" class="eye-toggle" onclick="togglePass('password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @if($errors->has('password'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('password') }}</p>
                    @else
                        <p class="field-hint">Optional now. Useful when delivery login is enabled.</p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="profile_image">Profile Image</label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*"
                           class="field-input {{ $errors->has('profile_image') ? 'error' : '' }}"
                           onchange="previewSingleImage(this, 'profileImagePreview')">
                    @if($errors->has('profile_image'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('profile_image') }}</p>
                    @else
                        <p class="field-hint">JPG, PNG, WEBP. Max 4MB.</p>
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
                              class="field-input {{ $errors->has('address') ? 'error' : '' }}">{{ old('address') }}</textarea>
                </div>

                <div class="field-group">
                    <label class="field-label" for="city">City</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-city icon"></i>
                        <input type="text" name="city" id="city" value="{{ old('city') }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="area">Area</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-map icon"></i>
                        <input type="text" name="area" id="area" value="{{ old('area') }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="pincode">Pincode</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-map-pin icon"></i>
                        <input type="text" name="pincode" id="pincode" value="{{ old('pincode') }}" class="field-input">
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
                        <input type="text" name="vehicle_type" id="vehicle_type" value="{{ old('vehicle_type') }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="vehicle_number">Vehicle Number</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-hashtag icon"></i>
                        <input type="text" name="vehicle_number" id="vehicle_number" value="{{ old('vehicle_number') }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="id_proof_type">ID Proof Type</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-id-badge icon"></i>
                        <input type="text" name="id_proof_type" id="id_proof_type" value="{{ old('id_proof_type') }}" class="field-input">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="id_proof_image">ID Proof Image</label>
                    <input type="file" name="id_proof_image" id="id_proof_image" accept="image/*"
                           class="field-input {{ $errors->has('id_proof_image') ? 'error' : '' }}"
                           onchange="previewSingleImage(this, 'idProofImagePreview')">
                    @if($errors->has('id_proof_image'))
                        <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('id_proof_image') }}</p>
                    @else
                        <p class="field-hint">JPG, PNG, WEBP. Max 4MB.</p>
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
                    <label class="role-checkbox-item {{ old('status', 1) ? 'checked' : '' }}">
                        <input type="checkbox" name="status" value="1" class="role-checkbox" {{ old('status', 1) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Active Status</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="fas fa-check"></i>
            Save Delivery Boy
        </button>

        <a href="{{ route('admin.delivery-boys.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>

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
