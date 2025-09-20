<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        if ($user) {
            $user->loadMissing(['currentProfile.role', 'profiles.role']);
        }

        return [
        ...parent::share($request),
        'auth' => [
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'avatar_url' => $user->avatar_url,
                'current_profile' => $user->currentProfile ? [
                    'id' => $user->currentProfile->id,
                    'name' => $user->currentProfile->name,
                    'role' => $user->currentProfile->role?->name,
                ] : null,
                'profiles' => $user->profiles->map(fn ($profile) => [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'role' => $profile->role?->name,
                    'is_default' => (bool) $profile->is_default,
                ])->toArray(),
            ] : null,
        ],
    ];
    }
}
