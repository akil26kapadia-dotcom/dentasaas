<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Auth::user()->notifications()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(string $id): JsonResponse|RedirectResponse
    {
        $notification = Auth::user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    public function readAll(): JsonResponse|RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }
}
