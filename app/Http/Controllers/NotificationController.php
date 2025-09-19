<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\GeneralNotification;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        try {
            $notification = Auth::user()->unreadNotifications->where('id', $request->id)->first();

            if (! $notification) {
                return $request->wantsJson()
                    ? response()->json(['success' => false, 'message' => 'Notificação não encontrada'], 404)
                    : back()->with('error', 'Notificação não encontrada');
            }

            $notification->markAsRead();

            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', true);
        } catch (\Exception $e) {
            Log::error('Erro ao marcar notificação como lida', [
                'id' => $request->id,
                'error' => $e->getMessage(),
            ]);

            return $request->wantsJson()
                ? response()->json(['success' => false], 500)
                : back()->with('error', 'Falha ao marcar notificação');
        }
    }
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    public function unread(Request $request)
    {
        $notifications = Auth::user()
            ->unreadNotifications()
            ->latest()
            ->paginate(5);

        return response()->json([
            'data' => $notifications->items(),
            'total_unread' => $notifications->total(),
        ]);
    }

    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->get();

        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications,
        ]);
    }

    public function create()
    {
        $users = User::select('id', 'name')
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Notifications/Send', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'required|string',
            'icon' => 'nullable|string',
            'users' => 'required|array',
            'notification_type' => 'required|in:internal,webpush,both'
        ]);

        $users = User::whereIn('id', $validated['users'])->get();

        $icon = $validated['icon'] ?: null;

        // Enviar notificação interna
        if (in_array($validated['notification_type'], ['internal', 'both'])) {
            foreach ($users as $user) {
                try {
                    $user->notify(new GeneralNotification(
                        $validated['title'],
                        $validated['message'],
                        $validated['link'],
                        $icon
                    ));

                    Log::info('Notificação interna enviada', [
                        'user_id' => $user->id,
                        'title' => $validated['title'],
                        'link' => $validated['link'],
                    ]);
                } catch (\Exception $e) {
                    Log::error('Falha ao enviar notificação interna', [
                        'user_id' => $user->id,
                        'title' => $validated['title'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        // Enviar notificação web push
        if (in_array($validated['notification_type'], ['webpush', 'both'])) {
            foreach ($users as $user) {
                try {
                    $payload = [
                        'title' => $validated['title'],
                        'body' => $validated['message'],
                        'icon' => $icon ?: '/img/notification.png',
                        'actions' => [
                            ['action' => 'Ver', 'title' => 'Explore Now', 'icon' => $icon ?: '/img/notification.png'],
                            ['action' => 'Fechar', 'title' => 'Dismiss', 'icon' => $icon ?: '/img/notification.png']
                        ],
                        'vibrate' => [200, 100, 200],
                        'data' => ['url' => $validated['link']],
                        'tag' => 'update-notification',
                        'renotify' => true,
                        'requireInteraction' => true
                    ];

                    $user->pushNotify($payload);

                    Log::info('Notificação web push enviada', [
                        'user_id' => $user->id,
                        'title' => $validated['title'],
                        'link' => $validated['link'],
                    ]);
                } catch (\Exception $e) {
                    Log::error('Falha ao enviar notificação web push', [
                        'user_id' => $user->id,
                        'title' => $validated['title'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return Redirect::route('notifications.index')
            ->with('success', 'Notificação enviada com sucesso!');
    }
}
