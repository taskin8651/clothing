@extends('layouts.admin')

@section('page-title', 'Show Category')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.categories.index') }}" class="admin-back-link">
            ← {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Category Details</h2>

        <p class="admin-page-subtitle">Full details for clothing category</p>
    </div>

    <div class="show-actions">
        @can('category_edit')
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Edit Category
            </a>
        @endcan

        @can('category_delete')
            <form action="{{ route('admin.categories.destroy', $category->id) }}"
                  method="POST"
                  onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
                @method('DELETE')
                @csrf

                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash-alt"></i>
                    Delete
                </button>
            </form>
        @endcan
    </div>
</div>

<div class="show-grid">

    <div>
        <div class="detail-card mb-3">
            <div class="profile-hero">
                <div style="width:100%; max-width:320px; height:240px; margin:0 auto; border-radius:24px; overflow:hidden; border:1px solid #E2E8F0; background:#F8FAFC;">
                    @if($category->category_image)
                        <img src="{{ $category->category_image['url'] }}" alt="{{ $category->name }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#94A3B8;font-size:54px;">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    @endif
                </div>

                <p class="profile-title">{{ $category->name }}</p>
                <p class="profile-subtitle">{{ $category->slug }}</p>

                @if($category->status)
                    <span class="status-pill success">
                        <i class="fas fa-check-circle"></i>
                        Active
                    </span>
                @else
                    <span class="status-pill warning">
                        <i class="fas fa-clock"></i>
                        Inactive
                    </span>
                @endif
            </div>

            <div class="detail-section-pad-sm">
                <div class="d-grid gap-2" style="grid-template-columns: 1fr 1fr;">
                    <div class="stat-mini">
                        <p class="stat-mini-label">Category ID</p>
                        <p class="stat-mini-value">#{{ $category->id }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Products</p>
                        <p class="stat-mini-value">{{ $category->products->count() }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Children</p>
                        <p class="stat-mini-value-sm">{{ $category->children->count() }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Sort</p>
                        <p class="stat-mini-value-sm">{{ $category->sort_order }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card detail-card-pad">
            <p class="quick-title">Quick Actions</p>

            <div class="quick-list">
                @can('category_edit')
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="quick-link primary">
                        <i class="fas fa-pencil-alt"></i>
                        Edit Category
                    </a>
                @endcan

                <a href="{{ route('admin.categories.index') }}" class="quick-link">
                    <i class="fas fa-list"></i>
                    All Categories
                </a>

                @can('category_create')
                    <a href="{{ route('admin.categories.create') }}" class="quick-link">
                        <i class="fas fa-plus"></i>
                        Add New Category
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div>
        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-info-circle"></i>
                </div>

                <p class="detail-section-title">Category Information</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">ID</span>
                    <span class="detail-value code-pill">#{{ $category->id }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">{{ $category->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Slug</span>
                    <span class="detail-value code-pill">{{ $category->slug }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Parent Category</span>
                    <span class="detail-value">{{ optional($category->parent)->name ?? 'No Parent' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Sort Order</span>
                    <span class="detail-value">{{ $category->sort_order }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">{{ optional($category->created_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Updated At</span>
                    <span class="detail-value">{{ optional($category->updated_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head between">
                <div class="d-flex align-items-center gap-2">
                    <div class="detail-section-icon">
                        <i class="fas fa-sitemap"></i>
                    </div>

                    <p class="detail-section-title">Sub Categories</p>
                </div>

                <span class="status-pill success">{{ $category->children->count() }} items</span>
            </div>

            <div class="detail-section-pad-sm">
                @if($category->children->count())
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($category->children as $child)
                            <span class="role-tag">{{ $child->name }}</span>
                        @endforeach
                    </div>
                @else
                    <div class="assign-empty">
                        <div class="assign-empty-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>

                        <p class="assign-empty-title">No sub categories</p>
                        <p class="assign-empty-text">This category has no child categories yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-section-head between">
                <div class="d-flex align-items-center gap-2">
                    <div class="detail-section-icon">
                        <i class="fas fa-box"></i>
                    </div>

                    <p class="detail-section-title">Category Products</p>
                </div>

                <span class="status-pill success">{{ $category->products->count() }} products</span>
            </div>

            <div class="detail-section-pad-sm">
                @if($category->products->count())
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($category->products->take(12) as $product)
                            <span class="role-tag">{{ $product->name }}</span>
                        @endforeach

                        @if($category->products->count() > 12)
                            <span class="role-tag">+{{ $category->products->count() - 12 }} more</span>
                        @endif
                    </div>
                @else
                    <div class="assign-empty">
                        <div class="assign-empty-icon">
                            <i class="fas fa-box-open"></i>
                        </div>

                        <p class="assign-empty-title">No products yet</p>
                        <p class="assign-empty-text">This category has no products assigned yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection