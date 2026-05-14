@php
    $get = fn ($key, $default = '') => old($key, $section->{$key} ?? $default);
@endphp

<div class="admin-form-grid">
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-image"></i></div>
            <div><p class="form-card-title">Section Content</p><p class="form-card-subtitle">Visual and copy shown on app/homepage</p></div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="title">Title <span class="req">*</span></label>
                <input type="text" name="title" id="title" value="{{ $get('title') }}" required class="field-input {{ $errors->has('title') ? 'error' : '' }}">
            </div>
            <div class="field-group">
                <label class="field-label" for="subtitle">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ $get('subtitle') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="image">Image</label>
                @if($section && $section->image_url)
                    <div class="setting-image-preview">
                        <img src="{{ $section->image_url }}" alt="{{ $section->title }}">
                        <label class="role-checkbox-item">
                            <input type="checkbox" name="remove_image" value="1" class="role-checkbox">
                            <div class="check-icon"></div>
                            <span class="checkbox-text">Remove Image</span>
                        </label>
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*" class="field-input {{ $errors->has('image') ? 'error' : '' }}" onchange="previewHomepageImage(this, 'homepageImagePreview')">
                <p class="field-hint">JPG, PNG, WEBP. Max 4MB.</p>
                <div id="homepageImagePreview" class="setting-live-preview"></div>
            </div>
            <div class="field-group">
                <label class="field-label" for="cta_text">CTA Text</label>
                <input type="text" name="cta_text" id="cta_text" value="{{ $get('cta_text') }}" class="field-input" placeholder="Shop Now">
            </div>
            <div class="field-group">
                <label class="field-label" for="link_url">Link URL</label>
                <input type="text" name="link_url" id="link_url" value="{{ $get('link_url') }}" class="field-input" placeholder="/shop/women">
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-sliders-h"></i></div>
            <div><p class="form-card-title">Targeting</p><p class="form-card-subtitle">Audience, section type and schedule</p></div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="type">Type</label>
                <select name="type" id="type" class="field-input">
                    @foreach(\App\Models\HomepageSection::TYPES as $key => $label)
                        <option value="{{ $key }}" {{ $get('type', 'banner') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label class="field-label" for="audience">Audience</label>
                <select name="audience" id="audience" class="field-input">
                    @foreach(\App\Models\HomepageSection::AUDIENCES as $key => $label)
                        <option value="{{ $key }}" {{ $get('audience', 'all') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label class="field-label" for="placement">Placement</label>
                <input type="text" name="placement" id="placement" value="{{ $get('placement', 'home') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="category_id">Target Category</label>
                <select name="category_id" id="category_id" class="field-input">
                    @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ (string) $get('category_id') === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label class="field-label" for="product_id">Target Product</label>
                <select name="product_id" id="product_id" class="field-input">
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ (string) $get('product_id') === (string) $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label class="field-label" for="starts_at">Starts At</label>
                <input type="datetime-local" name="starts_at" id="starts_at" value="{{ $get('starts_at') ? \Carbon\Carbon::parse($get('starts_at'))->format('Y-m-d\TH:i') : '' }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="ends_at">Ends At</label>
                <input type="datetime-local" name="ends_at" id="ends_at" value="{{ $get('ends_at') ? \Carbon\Carbon::parse($get('ends_at'))->format('Y-m-d\TH:i') : '' }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="sort_order">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ $get('sort_order', 0) }}" class="field-input">
            </div>
            <div class="checkbox-grid">
                <label class="role-checkbox-item {{ $get('status', 1) ? 'checked' : '' }}"><input type="checkbox" name="status" value="1" class="role-checkbox" {{ $get('status', 1) ? 'checked' : '' }}><div class="check-icon"></div><span class="checkbox-text">Active</span></label>
            </div>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Section</button>
    <a href="{{ route('admin.homepage-sections.index') }}" class="btn-ghost">Cancel</a>
</div>

@section('scripts')
@parent
<script>
function previewHomepageImage(input, targetId) {
    const target = document.getElementById(targetId);
    target.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            target.innerHTML = '<div class="setting-image-preview"><img src="' + e.target.result + '" alt="Preview"></div>';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<style>
.setting-image-preview{display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-bottom:12px}
.setting-image-preview img{width:180px;height:110px;object-fit:cover;border-radius:12px;border:1px solid #E2E8F0;background:#F8FAFC}
.setting-live-preview{margin-top:12px}
</style>
@endsection
