@extends('layouts.admin')

@section('page-title', 'Notifications')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Notifications</h2>

        <p class="admin-page-subtitle">
            Track orders, payments, returns, deliveries, invoices and system alerts
        </p>
    </div>

    @can('notification_mark_read')
        <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
            @csrf

            <button type="submit" class="btn-primary">
                <i class="fas fa-check-double"></i>
                Mark All Read
            </button>
        </form>
    @endcan
</div>

<div class="stats-grid">
    <div class="stat-card">
        <p class="stat-label">Total Notifications</p>
        <p class="stat-value">{{ $notifications->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Unread</p>
        <p class="stat-value">{{ $notifications->where('is_read', 0)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Read</p>
        <p class="stat-value">{{ $notifications->where('is_read', 1)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Today</p>
        <p class="stat-value">
            {{ $notifications->filter(fn($item) => optional($item->created_at)->isToday())->count() }}
        </p>
    </div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Notifications</p>

        <span class="page-card-note">
            <i class="fas fa-info-circle"></i>
            Latest system and marketplace alerts
        </span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-Notification">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Notification</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($notifications as $notification)
                    <tr data-entry-id="{{ $notification->id }}" class="{{ ! $notification->is_read ? 'notification-unread-row' : '' }}">
                        <td></td>

                        <td>
                            <span class="id-text">#{{ $notification->id }}</span>
                        </td>

                        <td>
                            <div class="inline-flex-center">
                                <div class="notification-icon" style="background: {{ $notification->color ?: '#4F46E5' }};">
                                    <i class="{{ $notification->icon ?: 'fas fa-bell' }}"></i>
                                </div>

                                <div>
                                    <p class="table-main-text">
                                        {{ $notification->title ?: 'Notification' }}
                                    </p>

                                    <p class="table-sub-text">
                                        {{ \Illuminate\Support\Str::limit($notification->message, 80) }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="role-tag">
                                {{ \App\Models\Notification::types()[$notification->type] ?? ucfirst($notification->type ?: 'System') }}
                            </span>
                        </td>

                        <td>
                            @if($notification->is_read)
                                <span class="status-pill success">Read</span>
                            @else
                                <span class="status-pill warning">Unread</span>
                            @endif
                        </td>

                        <td>
                            <p class="table-main-text">
                                {{ optional($notification->created_at)->format('d M Y') }}
                            </p>
                            <p class="table-sub-text">
                                {{ optional($notification->created_at)->format('h:i A') }}
                            </p>
                        </td>

                        <td>
                            <div class="action-row">
                                @can('notification_show')
                                    <a href="{{ route('admin.notifications.show', $notification->id) }}" class="btn-outline">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                @endcan

                                @if(! $notification->is_read)
                                    @can('notification_mark_read')
                                        <form action="{{ route('admin.notifications.markRead', $notification->id) }}"
                                              method="POST"
                                              style="display:inline;">
                                            @csrf

                                            <button type="submit" class="btn-outline btn-outline-edit">
                                                <i class="fas fa-check"></i>
                                                Read
                                            </button>
                                        </form>
                                    @endcan
                                @endif

                                @can('notification_delete')
                                    <form action="{{ route('admin.notifications.destroy', $notification->id) }}"
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
    initAdminDataTable('.datatable-Notification', {
        canDelete: @can('notification_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.notifications.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search notifications...',
        infoText: 'Showing _START_–_END_ of _TOTAL_ notifications'
    });
});
</script>

<style>
.notification-icon{
    width:42px;
    height:42px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    flex:0 0 42px;
    box-shadow:0 10px 20px rgba(15,23,42,.12);
}

.notification-unread-row{
    background:#F8FAFC;
}

.notification-unread-row .table-main-text{
    font-weight:900;
}
</style>

@endsection