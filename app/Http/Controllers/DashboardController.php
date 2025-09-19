<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();

        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('active', true)->count(),
            'users_with_2fa' => User::where('active_2fa', true)->count(),
            'unread_notifications' => $user?->unreadNotifications()->count() ?? 0,
        ];

        $recentUsers = User::query()
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'email', 'created_at']);

        $recentNotifications = $user
            ? $user->notifications()->latest()->take(5)->get()->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => data_get($notification->data, 'title'),
                    'message' => data_get($notification->data, 'message'),
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                ];
            })
            : collect();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentNotifications' => $recentNotifications,
        ]);
    }
}
