<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('dashboard.notifications', [
            'notifications' => $user->notifications()->paginate(),
        ]);
    }

    public function read(string $id)
    {
        $user = Auth::user();
        $notification = $user->unreadNotifications()->findOrFail($id);

        $notification->markAsRead();

        return redirect()->route('dashboard.notifications.index');
    }

    public function unread(string $id)
    {
        $user = Auth::user();
        $notification = $user->readNotifications()->findOrFail($id);

        $notification->markAsUnread();

        return redirect()->route('dashboard.notifications.index');
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        $notification->delete();

        return redirect()->route('dashboard.notifications.index');
    }
    public function markAllRead()
    {
        $user = auth()->user();

        $user->unreadNotifications->markAsRead();

        return back();
    }
    public function markAllUnRead()
    {
        $user = auth()->user();

        $user->readNotifications->markAsUnread();

        return back();
    }
}
