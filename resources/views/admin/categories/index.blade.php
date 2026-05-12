@extends('layouts.admin')

@section('page-title', 'Categories')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Categories</h2>
        <p class="admin-page-subtitle">Manage clothing categories and sub categories</p>
    </div>

    @can('category_create')
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Add Category
        </a>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card">
        <p class="stat-label">Total Categories</p>
        <p class="stat-value">{{ $categories->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Active</p>
        <p class="stat-value">{{ $categories->where('status', 1)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Parent Categories</p>
        <p class="stat-value">{{ $categories->whereNull('parent_id')->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Products</p>
        <p class="stat-value">{{ $categories->sum(fn($category) => $category->products->count()) }}</p>
    </div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Categories</p>

        <span class="page-card-note">
            <i class="fas fa-info-circle"></i>
            Parent category se category hierarchy manage hogi
        </span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-Category">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Parent</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($categories as $category)
                    <tr data-entry-id="{{ $category->id }}">
                        <td></td>

                        <td>
                            <span class="id-text">#{{ $category->id }}</span>
                        </td>

                        <td>
                            <div class="inline-flex-center">
                                <div class="avatar-circle" style="background:#F8FAFC; border:1px solid #E2E8F0; overflow:hidden;">
                                    @if($category->category_image)
                                        <img src="{{ $category->category_image['url'] }}"
                                             alt="{{ $category->name }}"
                                             style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <i class="fas fa-layer-group" style="color:#64748B;"></i>
                                    @endif
                                </div>

                                <div>
                                    <p class="table-main-text">{{ $category->name }}</p>
                                    <p class="table-sub-text">Sort: {{ $category->sort_order }}</p>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="role-tag">{{ optional($category->parent)->name ?? 'Parent' }}</span>
                        </td>

                        <td>
                            <span class="code-pill">{{ $category->slug }}</span>
                        </td>

                        <td>
                            <span class="status-pill success">{{ $category->products->count() }}</span>
                        </td>

                        <td>
                            @if($category->status)
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-dot status-success"></span>
                                    <span style="font-size:12.5px; color:#166534;">Active</span>
                                </div>
                            @else
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-dot status-warning"></span>
                                    <span style="font-size:12.5px; color:#92400E;">Inactive</span>
                                </div>
                            @endif
                        </td>

                        <td>
                            <div class="action-row">
                                @can('category_show')
                                    <a href="{{ route('admin.categories.show', $category->id) }}" class="btn-outline">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                @endcan

                                @can('category_edit')
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-outline btn-outline-edit">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </a>
                                @endcan

                                @can('category_delete')
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                          method="POST"
                                          style="display:inline;"
                                          onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
                                        @method('DELETE')
                                        @csrf

                                        <button type="submit" class="btn-outline btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(function () {
    initAdminDataTable('.datatable-Category', {
        canDelete: @can('category_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.categories.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search categories...',
        infoText: 'Showing _START_–_END_ of _TOTAL_ categories'
    });
});
</script>
@endsection