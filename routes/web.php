<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;

use App\Http\Controllers\{
    ProfileController,
    RoleController,
    PermissionController,
    RoleUserController,
    UserController,
    TwoFactorAuthController,
    MenuSideBarController,
    AppConfigController,
    AuditController,
    NotificationController,
    PushSubscriptionController,
    DashboardController,
    PublicSearchController,
    UserAdditionalDataController,
    UserAddressController,
    ProfileSwitchController,
};

Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/', [PublicSearchController::class, 'index'])->name('public.search');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// 2FA
Route::middleware('auth')->group(function () {
    Route::get('/two-factor', fn () => Inertia::render('Auth/TwoFactorAuth'))->name('two-factor');

    Route::post('/two-factor/verify', function (Request $request) {
        $request->validate(['code' => 'required|digits:6']);
        $user = $request->user();
        $secret = Crypt::decrypt($user->google2fa_secret);
        $isValid = (new Google2FA())->verifyKey($secret, $request->input('code'));

        if (! $isValid) {
            return response()->json(['message' => 'Invalid code'], 422);
        }

        session(['2fa_passed' => true]);
        return response()->json(['redirect' => route('dashboard')]);
    });
});

// Perfil
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('updateAvatar');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');

    Route::post('/additional-data', [UserAdditionalDataController::class, 'store'])->name('additional-data.store');
    Route::put('/additional-data', [UserAdditionalDataController::class, 'update'])->name('additional-data.update');
    Route::delete('/additional-data', [UserAdditionalDataController::class, 'destroy'])->name('additional-data.destroy');

    Route::post('/address', [UserAddressController::class, 'store'])->name('address.store');
    Route::put('/address', [UserAddressController::class, 'update'])->name('address.update');
    Route::delete('/address', [UserAddressController::class, 'destroy'])->name('address.destroy');

    Route::post('/switch-profile', ProfileSwitchController::class)->name('switch');

    Route::get('/2fa/setup', [TwoFactorAuthController::class, 'show'])->name('2fa.setup');
    Route::post('/2fa/enable', [TwoFactorAuthController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/disable', [TwoFactorAuthController::class, 'disable'])->name('2fa.disable');
});

// Administração (admin)
Route::middleware(['auth', 'role:admin|master'])->group(function () {
    Route::controller(RoleUserController::class)->prefix('roles-user')->name('roles-user.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'assign')->name('assign');
        Route::delete('/', 'remove')->name('remove');
    });

    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
        Route::post('/{user}/toggle-status', 'toggleStatus')->name('toggle-status');
        Route::post('/{user}/toggle-2fa', 'toggle2FA')->name('toggle-2fa');
    });

    Route::middleware('permission:audits-all')->group(function () {
        Route::get('audits', [AuditController::class, 'index'])->name('audits.index');
        Route::get('audits/{audit}', [AuditController::class, 'show'])->name('audits.show');
        Route::delete('audits/{audit}', [AuditController::class, 'destroy'])->name('audits.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    Route::post('push-subscriptions', [PushSubscriptionController::class, 'store'])->name('push-subscriptions.store');
});

Route::middleware(['auth', 'permission:notification-all'])->group(function () {
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('notifications/send', [NotificationController::class, 'store'])->name('notifications.send');
});

Route::middleware(['auth', 'restrict.system.access'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('menus', MenuSideBarController::class);
});

Route::middleware(['auth','can:configs-all'])->group(function () {
    Route::resource('config', AppConfigController::class);
});

require __DIR__.'/auth.php';
