<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('notifiable_id', auth()->id())
            ->where('notifiable_type', get_class(auth()->user()))
            ->latest()
            ->paginate(20);

        return view('frontend.notifications.index', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        $this->authorizeNotification($notification);
        $notification->markAsRead();

        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return redirect()->route('frontend.notifications.index');
    }

    public function markRead(Notification $notification)
    {
        $this->authorizeNotification($notification);
        $notification->markAsRead();

        return back()->with('message', 'Notification read mark ho gayi.');
    }

    public function markAllRead()
    {
        Notification::where('notifiable_id', auth()->id())
            ->where('notifiable_type', get_class(auth()->user()))
            ->where('is_read', 0)
            ->update([
                'is_read' => 1,
                'read_at' => now(),
            ]);

        return back()->with('message', 'All notifications read mark ho gayi.');
    }

    private function authorizeNotification(Notification $notification): void
    {
        abort_if((int) $notification->notifiable_id !== (int) auth()->id(), 403);
        abort_if($notification->notifiable_type !== get_class(auth()->user()), 403);
    }
}
