<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('notification_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $notifications = Notification::latest()
            ->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        abort_if(Gate::denies('notification_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (! $notification->is_read) {
            $notification->markAsRead();
        }

        return view('admin.notifications.show', compact('notification'));
    }

    public function markRead(Notification $notification)
    {
        abort_if(Gate::denies('notification_mark_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $notification->markAsRead();

        return back()->with('message', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        abort_if(Gate::denies('notification_mark_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Notification::where('is_read', 0)->update([
            'is_read' => 1,
            'read_at' => now(),
        ]);

        return back()->with('message', 'All notifications marked as read.');
    }

    public function destroy(Notification $notification)
    {
        abort_if(Gate::denies('notification_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $notification->delete();

        return back()->with('message', 'Notification deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('notification_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Notification::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}