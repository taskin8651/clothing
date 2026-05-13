@can('notification_access')
    @php
        $notificationsTableReady = class_exists(\App\Models\Notification::class)
            && \Illuminate\Support\Facades\Schema::hasTable('notifications');

        $headerNotifications = $notificationsTableReady
            ? \App\Models\Notification::latest()->limit(8)->get()
            : collect();

        $unreadNotificationsCount = $notificationsTableReady
            ? \App\Models\Notification::where('is_read', 0)->count()
            : 0;
    @endphp

    <div x-data="{ open:false }" class="relative notification-modal-wrap">
        <button type="button"
                class="notification-trigger {{ request()->is('admin/notifications*') ? 'active' : '' }}"
                @click="open = true"
                title="Notifications">
            <i class="fas fa-bell"></i>

            @if($unreadNotificationsCount > 0)
                <span class="notification-badge">{{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}</span>
            @endif
        </button>

        <div x-show="open"
             x-cloak
             x-transition.opacity
             class="notification-backdrop"
             @click="open = false"></div>

        <section x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 @click.outside="open = false"
                 class="notification-panel">

            <div class="notification-panel-head">
                <div>
                    <p class="notification-panel-title">Notifications</p>
                    <p class="notification-panel-subtitle">{{ $unreadNotificationsCount }} unread alerts</p>
                </div>

                <button type="button" class="notification-close" @click="open = false">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="notification-panel-actions">
                <a href="{{ route('admin.notifications.index') }}" class="notification-action-link">
                    <i class="fas fa-list"></i>
                    View All
                </a>

                @if($unreadNotificationsCount > 0)
                    @can('notification_mark_read')
                        <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="notification-action-link">
                                <i class="fas fa-check-double"></i>
                                Mark All Read
                            </button>
                        </form>
                    @endcan
                @endif
            </div>

            <div class="notification-list">
                @forelse($headerNotifications as $notification)
                    <div class="notification-item {{ ! $notification->is_read ? 'is-unread' : '' }}">
                        <div class="notification-item-icon" style="background: {{ $notification->color ?: '#4F46E5' }};">
                            <i class="{{ $notification->icon ?: 'fas fa-bell' }}"></i>
                        </div>

                        <div class="notification-item-body">
                            <div class="notification-item-top">
                                <p class="notification-item-title">{{ $notification->title ?: 'Notification' }}</p>
                                <span class="notification-time">{{ optional($notification->created_at)->diffForHumans() }}</span>
                            </div>

                            <p class="notification-item-message">
                                {{ \Illuminate\Support\Str::limit($notification->message, 95) }}
                            </p>

                            <div class="notification-item-footer">
                                <span class="notification-type">
                                    {{ \App\Models\Notification::types()[$notification->type] ?? ucfirst($notification->type ?: 'System') }}
                                </span>

                                <div class="notification-inline-actions">
                                    @can('notification_show')
                                        <a href="{{ route('admin.notifications.show', $notification->id) }}">Open</a>
                                    @endcan

                                    @if(! $notification->is_read)
                                        @can('notification_mark_read')
                                            <form action="{{ route('admin.notifications.markRead', $notification->id) }}" method="POST">
                                                @csrf
                                                <button type="submit">Read</button>
                                            </form>
                                        @endcan
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="notification-empty">
                        <i class="fas fa-bell-slash"></i>
                        <p>No notifications yet.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <style>
        [x-cloak] { display: none !important; }

        .notification-modal-wrap {
            display: inline-flex;
        }

        .notification-trigger {
            align-items: center;
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            color: #475569;
            display: inline-flex;
            height: 42px;
            justify-content: center;
            position: relative;
            transition: all .18s ease;
            width: 42px;
        }

        .notification-trigger:hover,
        .notification-trigger.active {
            background: #EEF2FF;
            border-color: #C7D2FE;
            color: #4F46E5;
        }

        .notification-badge {
            align-items: center;
            background: #EF4444;
            border: 2px solid #fff;
            border-radius: 999px;
            color: #fff;
            display: flex;
            font-size: 10px;
            font-weight: 800;
            height: 20px;
            justify-content: center;
            min-width: 20px;
            padding: 0 5px;
            position: absolute;
            right: -6px;
            top: -6px;
        }

        .notification-backdrop {
            background: rgba(15, 23, 42, .18);
            inset: 0;
            position: fixed;
            z-index: 1040;
        }

        .notification-panel {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 18px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .22);
            max-height: min(680px, calc(100vh - 96px));
            overflow: hidden;
            position: fixed;
            right: 24px;
            top: 72px;
            width: min(430px, calc(100vw - 32px));
            z-index: 1050;
        }

        .notification-panel-head {
            align-items: center;
            border-bottom: 1px solid #EEF2F7;
            display: flex;
            justify-content: space-between;
            padding: 16px 18px;
        }

        .notification-panel-title {
            color: #0F172A;
            font-size: 16px;
            font-weight: 800;
            margin: 0;
        }

        .notification-panel-subtitle {
            color: #64748B;
            font-size: 12px;
            margin: 2px 0 0;
        }

        .notification-close {
            align-items: center;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            color: #64748B;
            display: flex;
            height: 32px;
            justify-content: center;
            width: 32px;
        }

        .notification-panel-actions {
            align-items: center;
            background: #F8FAFC;
            border-bottom: 1px solid #EEF2F7;
            display: flex;
            gap: 8px;
            justify-content: space-between;
            padding: 10px 14px;
        }

        .notification-action-link {
            align-items: center;
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 999px;
            color: #334155;
            display: inline-flex;
            font-size: 12px;
            font-weight: 800;
            gap: 7px;
            padding: 7px 11px;
            text-decoration: none;
        }

        button.notification-action-link {
            cursor: pointer;
        }

        .notification-list {
            max-height: calc(min(680px, 100vh - 96px) - 116px);
            overflow-y: auto;
            padding: 6px;
        }

        .notification-item {
            border-radius: 14px;
            display: flex;
            gap: 12px;
            padding: 12px;
        }

        .notification-item + .notification-item {
            border-top: 1px solid #F1F5F9;
        }

        .notification-item.is-unread {
            background: #F8FAFC;
        }

        .notification-item-icon {
            align-items: center;
            border-radius: 13px;
            color: #fff;
            display: flex;
            flex: 0 0 40px;
            height: 40px;
            justify-content: center;
            width: 40px;
        }

        .notification-item-body {
            min-width: 0;
            width: 100%;
        }

        .notification-item-top,
        .notification-item-footer {
            align-items: center;
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }

        .notification-item-title {
            color: #0F172A;
            font-size: 13px;
            font-weight: 800;
            margin: 0;
        }

        .notification-time {
            color: #94A3B8;
            flex: 0 0 auto;
            font-size: 11px;
            font-weight: 700;
        }

        .notification-item-message {
            color: #64748B;
            font-size: 12px;
            line-height: 1.55;
            margin: 4px 0 8px;
        }

        .notification-type {
            background: #EEF2FF;
            border-radius: 999px;
            color: #4338CA;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 8px;
        }

        .notification-inline-actions {
            align-items: center;
            display: flex;
            gap: 10px;
        }

        .notification-inline-actions a,
        .notification-inline-actions button {
            background: transparent;
            border: 0;
            color: #4F46E5;
            cursor: pointer;
            font-size: 12px;
            font-weight: 800;
            padding: 0;
            text-decoration: none;
        }

        .notification-empty {
            align-items: center;
            color: #94A3B8;
            display: flex;
            flex-direction: column;
            gap: 8px;
            justify-content: center;
            min-height: 180px;
            padding: 24px;
        }

        .notification-empty i {
            font-size: 24px;
        }

        .notification-empty p {
            font-size: 13px;
            font-weight: 700;
            margin: 0;
        }

        @media (max-width: 640px) {
            .notification-panel {
                right: 16px;
                top: 64px;
            }
        }
    </style>
@endcan
