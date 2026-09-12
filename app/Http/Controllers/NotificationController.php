<?php

namespace App\Http\Controllers;

use App\Models\ArtistNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, ArtistNotification $notification): RedirectResponse
    {
        abort_unless($notification->artist_id === $request->user()->id, 403);

        if (! $notification->isRead()) {
            $notification->update(['read_at' => now()]);
        }

        return $notification->url
            ? redirect($notification->url)
            : back();
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->notifications()->unread()->update(['read_at' => now()]);

        return redirect()->route('notifications.index');
    }
}