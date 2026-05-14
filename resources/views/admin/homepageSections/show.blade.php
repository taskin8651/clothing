@extends('layouts.admin')

@section('page-title', 'Homepage Section Details')

@section('content')
<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.homepage-sections.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a>
        <h2 class="admin-page-title">{{ $homepageSection->title }}</h2>
        <p class="admin-page-subtitle">{{ \App\Models\HomepageSection::TYPES[$homepageSection->type] ?? $homepageSection->type }}</p>
    </div>
    @can('homepage_section_edit')
        <a href="{{ route('admin.homepage-sections.edit', $homepageSection) }}" class="btn-primary"><i class="fas fa-pencil-alt"></i> Edit Section</a>
    @endcan
</div>

<div class="page-card">
    <div class="page-card-header"><p class="page-card-title">Section Details</p></div>
    @if($homepageSection->image_url)
        <div style="padding:18px 18px 0;">
            <img src="{{ $homepageSection->image_url }}" alt="{{ $homepageSection->title }}" style="width:100%;max-height:320px;object-fit:cover;border-radius:12px;border:1px solid #E2E8F0;">
        </div>
    @endif
    <div class="show-grid">
        <div class="show-item"><span>Subtitle</span><strong>{{ $homepageSection->subtitle ?: 'Not set' }}</strong></div>
        <div class="show-item"><span>Audience</span><strong>{{ ucfirst($homepageSection->audience) }}</strong></div>
        <div class="show-item"><span>Placement</span><strong>{{ $homepageSection->placement }}</strong></div>
        <div class="show-item"><span>Category</span><strong>{{ optional($homepageSection->category)->name ?: 'Not set' }}</strong></div>
        <div class="show-item"><span>Product</span><strong>{{ optional($homepageSection->product)->name ?: 'Not set' }}</strong></div>
        <div class="show-item"><span>CTA</span><strong>{{ $homepageSection->cta_text ?: 'Not set' }}</strong></div>
        <div class="show-item"><span>Link</span><strong>{{ $homepageSection->link_url ?: 'Not set' }}</strong></div>
        <div class="show-item"><span>Status</span><strong>{{ $homepageSection->status ? 'Active' : 'Inactive' }}</strong></div>
    </div>
</div>
@endsection
