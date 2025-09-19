<?php

namespace App\Providers;

use App\Models\MenuSideBar;
use App\Models\AppConfig;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use OwenIt\Auditing\AuditableObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * @var \App\Models\User|null $user
     */
    public function boot(): void
    {
        User::observe(AuditableObserver::class);

        Vite::prefetch(concurrency: 3);

        $appName   = config('app.name', 'Justiça Federal');
        $appIcon   = asset('images/logo.png');
        $appBanner = null;

        try {
            $configs = AppConfig::whereIn('key', ['app_name', 'icon_app', 'banner_principal'])
                ->get()
                ->keyBy('key');

            $appName   = data_get($configs, 'app_name.value', $appName);
            $appIcon   = data_get($configs, 'icon_app.media_url', $appIcon);
            $appBanner = data_get($configs, 'banner_principal.media_url', $appBanner);
        } catch (\Throwable $e) {
            // Banco de dados não configurado ou tabela inexistente.
        }

        config([
            'app.name'   => $appName,
            'app.icon'   => $appIcon,
            'app.banner' => $appBanner,
        ]);

        Inertia::share([
            'auth' => [
                'user' => Auth::check() ? [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->getRoleNames()->first(),
                    'roles' => Auth::user()->getRoleNames()->toArray(),
                    'permissions' => Auth::user()->getAllPermissions()->pluck('name')->toArray(),
                    'notifications_count' => Auth::user()->unreadNotifications()->count(),
                ] : null,
            ],
            'sideMenus' => function () {
                $user = Auth::user();
                if (!$user) {
                    return [];
                }
                return MenuSideBar::query()
                    ->where('active', true)
                    // primeiro agrupa por nível (1, 2, 3…)
                    ->orderBy('level')
                    // depois ordena dentro de cada nível pelo seu campo order
                    ->orderBy('order')
                    // opcional: só seleciona as colunas que você usa no Vue
                    ->get([
                        'id',
                        'description',
                        'route',
                        'icon',
                        'parent_id',
                        'level',
                        'style',
                        'acl',
                        'active',
                        'group',
                    ])
                    // opcional: filtra no backend pelas permissões do usuário
                    ->filter(fn($menu) => !$menu->acl || $user->can($menu->acl))
                    ->values();
            },
            'app' => [
                'name'   => $appName,
                'icon'   => $appIcon,
                'banner' => $appBanner,
            ],
        ]);
    }
}
