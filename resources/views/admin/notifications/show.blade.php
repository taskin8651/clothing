@extends('layouts.admin')

@section('page-title', 'Show Notification')

@section('content')

<div class="admin-page-head">
    <div>
        <a href="{{ route('admin.notifications.index') }}" class="admin-back-link">
            ← {{ trans('global.back_to_list') }}
        </a>

        <h2 class="admin-page-title">Notification Details</h2>

        <p class="admin-page-subtitle">
            View notification message, type, status and related action
        </p>
    </div>

    <div class="show-actions">
        @if($notification->action_url)
            <a href="{{ $notification->action_url }}" class="btn-primary">
                <i class="fas fa-external-link-alt"></i>
                Open Related
            </a>
        @endif

        @can('notification_delete')
            <form action="{{ route('admin.notifications.destroy', $notification->id) }}"
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
                <div class="profile-avatar-lg" style="background: {{ $notification->color ?: '#4F46E5' }};">
                    <i class="{{ $notification->icon ?: 'fas fa-bell' }}"></i>
                </div>

                <p class="profile-title">{{ $notification->title ?: 'Notification' }}</p>

                <p class="profile-subtitle">
                    {{ \App\Models\Notification::types()[$notification->type] ?? ucfirst($notification->type ?: 'System') }}
                </p>

                @if($notification->is_read)
                    <span class="status-pill success">
                        <i class="fas fa-check-circle"></i>
                        Read
                    </span>
                @else
                    <span class="status-pill warning">
                        <i class="fas fa-clock"></i>
                        Unread
                    </span>
                @endif
            </div>

            <div class="detail-section-pad-sm">
                <div class="d-grid gap-2" style="grid-template-columns: 1fr 1fr;">
                    <div class="stat-mini">
                        <p class="stat-mini-label">Notification ID</p>
                        <p class="stat-mini-value">#{{ $notification->id }}</p>
                    </div>

                    <div class="stat-mini">
                        <p class="stat-mini-label">Type</p>
                        <p class="stat-mini-value-sm">
                            {{ ucfirst($notification->type ?: 'System') }}
                        </p>
                    </div>

                    <div class="stat-mini" style="grid-column: span 2;">
                        <p class="stat-mini-label">Created</p>
                        <p class="stat-mini-value-sm">
                            {{ optional($notification->created_at)->format('d M Y, h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card detail-card-pad">
            <p class="quick-title">Quick Actions</p>

            <div class="quick-list">
                @if($notification->action_url)
                    <a href="{{ $notification->action_url }}" class="quick-link primary">
                        <i class="fas fa-external-link-alt"></i>
                        Open Related
                    </a>
                @endif

                <a href="{{ route('admin.notifications.index') }}" class="quick-link">
                    <i class="fas fa-list"></i>
                    All Notifications
                </a>

                @if(! $notification->is_read)
                    @can('notification_mark_read')
                        <form action="{{ route('admin.notifications.markRead', $notification->id) }}"
                              method="POST">
                            @csrf

                            <button type="submit" class="quick-link" style="width:100%; border:0;">
                                <i class="fas fa-check"></i>
                                Mark As Read
                            </button>
                        </form>
                    @endcan
                @endif
            </div>
        </div>
    </div>

    <div>
        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-bell"></i>
                </div>

                <p class="detail-section-title">Notification Information</p>
            </div>

            <div class="detail-section-body">

                <div class="detail-row">
                    <span class="detail-label">Title</span>
                    <span class="detail-value">{{ $notification->title ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Type</span>
                    <span class="detail-value code-pill">{{ $notification->type ?: 'system' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status</span>

                    @if($notification->is_read)
                        <span class="status-pill success">Read</span>
                    @else
                        <span class="status-pill warning">Unread</span>
                    @endif
                </div>

                <div class="detail-row">
                    <span class="detail-label">Read At</span>
                    <span class="detail-value">
                        {{ $notification->read_at ? $notification->read_at->format('d M Y, h:i A') : '-' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">
                        {{ optional($notification->created_at)->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-align-left"></i>
                </div>

                <p class="detail-section-title">Message</p>
            </div>

            <div class="detail-section-pad-sm">
                <p class="notification-message">
                    {{ $notification->message ?: '-' }}
                </p>
            </div>
        </div>

        <div class="detail-card mb-3">
            <div class="detail-section-head">
                <div class="detail-section-icon">
                    <i class="fas fa-link"></i>
                </div>

                <p class="detail-section-title">Related Action</p>
            </div>

            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-label">Action URL</span>

                    @if($notification->action_url)
                        <a href="{{ $notification->action_url }}" class="detail-value">
                            {{ $notification->action_url }}
                        </a>
                    @else
                        <span class="detail-value">-</span>
                    @endif
                </div>

                <div class="detail-row">
                    <span class="detail-label">Related Type</span>
                    <span class="detail-value code-pill">{{ $notification->related_type ?: '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Related ID</span>
                    <span class="detail-value">{{ $notification->related_id ?: '-' }}</span>
                </div>
            </div>
        </div>

        @if($notification->data)
            <div class="detail-card">
                <div class="detail-section-head">
                    <div class="detail-section-icon">
                        <i class="fas fa-code"></i>
                    </div>

                    <p class="detail-section-title">Extra Data</p>
                </div>

                <div class="detail-section-pad-sm">
                    <pre class="notification-json">{{ json_encode($notification->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif
    </div>

</div>

<style>
.notification-message{
    margin:0;
    color:#334155;
    font-size:14px;
    line-height:1.7;
}

.notification-json{
    background:#0F172A;
    color:#E2E8F0;
    border-radius:16px;
    padding:16px;
    font-size:12px;
    line-height:1.6;
    overflow:auto;
}
</style>

@endsection