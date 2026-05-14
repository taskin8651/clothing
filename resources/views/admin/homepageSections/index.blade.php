@extends('layouts.admin')

@section('page-title', 'Homepage Sections')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Homepage Sections</h2>
        <p class="admin-page-subtitle">Manage Zilo-style app banners, collections, offers and audience blocks</p>
    </div>

    @can('homepage_section_create')
        <a href="{{ route('admin.homepage-sections.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Add Section
        </a>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card"><p class="stat-label">Total Sections</p><p class="stat-value">{{ $homepageSections->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">Active</p><p class="stat-value">{{ $homepageSections->where('status', 1)->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">Women</p><p class="stat-value">{{ $homepageSections->where('audience', 'women')->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">Offers</p><p class="stat-value">{{ $homepageSections->where('type', 'offer')->count() }}</p></div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">Merchandising Blocks</p>
        <span class="page-card-note"><i class="fas fa-info-circle"></i> Frontend/app homepage ke visual sections yahan se control honge</span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-HomepageSection">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Section</th>
                    <th>Type</th>
                    <th>Audience</th>
                    <th>Target</th>
                    <th>Schedule</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($homepageSections as $section)
                    <tr data-entry-id="{{ $section->id }}">
                        <td></td>
                        <td><span class="id-text">#{{ $section->id }}</span></td>
                        <td>
                            <div class="inline-flex-center">
                                <div class="avatar-circle" style="background:#F8FAFC; border:1px solid #E2E8F0; overflow:hidden;">
                                    @if($section->image_url)
                                        <img src="{{ $section->image_url }}" alt="{{ $section->title }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <i class="fas fa-image" style="color:#64748B;"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="table-main-text">{{ $section->title }}</p>
                                    <p class="table-sub-text">{{ $section->subtitle ?: 'Sort: ' . $section->sort_order }}</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="role-tag">{{ \App\Models\HomepageSection::TYPES[$section->type] ?? $section->type }}</span></td>
                        <td><span class="code-pill">{{ ucfirst($section->audience) }}</span></td>
                        <td>
                            <p class="table-main-text">{{ optional($section->category)->name ?: optional($section->product)->name ?: $section->link_url ?: 'No target' }}</p>
                            <p class="table-sub-text">{{ $section->placement }}</p>
                        </td>
                        <td>
                            <p class="table-sub-text">{{ $section->starts_at ? $section->starts_at->format('d M Y') : 'Always' }}</p>
                            <p class="table-sub-text">{{ $section->ends_at ? 'to ' . $section->ends_at->format('d M Y') : '' }}</p>
                        </td>
                        <td>
                            @if($section->status)
                                <span class="status-pill success">Active</span>
                            @else
                                <span class="status-pill warning">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-row">
                                @can('homepage_section_show')
                                    <a href="{{ route('admin.homepage-sections.show', $section) }}" class="btn-outline"><i class="fas fa-eye"></i> View</a>
                                @endcan
                                @can('homepage_section_edit')
                                    <a href="{{ route('admin.homepage-sections.edit', $section) }}" class="btn-outline btn-outline-edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                @endcan
                                @can('homepage_section_delete')
                                    <form action="{{ route('admin.homepage-sections.destroy', $section) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn-outline btn-outline-danger"><i class="fas fa-trash-alt"></i> Delete</button>
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
    initAdminDataTable('.datatable-HomepageSection', {
        canDelete: @can('homepage_section_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.homepage-sections.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search homepage sections...',
        infoText: 'Showing _START_-_END_ of _TOTAL_ sections'
    });
});
</script>
@endsection
