<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;

// Controllers
use App\Http\Controllers\{
    ProfileController,
    RoleController,
    PermissionController,
    RoleUserController,
    UserController,
    TwoFactorAuthController,
    MenuSideBarController,
    AppConfigController,
    ReportController,
    NotificationController,
    PushSubscriptionController,
    AgendaAiAddressEstablishmentController,
    AgendaAiAppointmentController,
    AgendaAiClientController,
    AgendaAiMessageController,
    AgendaAiPaymentController,
    AgendaAiPhoneController,
    AgendaAiPlanController,
    AgendaAiProfessionalController,
    AgendaAiScheduleController,
    AgendaAiScheduleAvailabilityController,
    AgendaAiServiceController,
    AuditController,
    PublicChatController,
    PublicSearchController,
    PublicProfessionalController,
    AgendaAiMessageSettingController,
    AgendaAiChatLinkController,
    DashboardController,
    AgendaAiSettingsController,
    AgendaAiRequestController,
    ServiceReviewController
};


//Home e Dashboard protegidos com redirecionamento
Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
// 1) Home pública agora vai pro seu Welcome.vue (walcame)

Route::get('/', [PublicSearchController::class, 'index'])
     ->name('public.search');
Route::get('/profile/{professional:uuid}', [PublicProfessionalController::class, 'show'])
     ->name('profile.show');

// 6) Dashboard protegido
Route::middleware(['auth','verified','establishment.redirect'])->group(function(){
  Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
  Route::get('/dashboard/week-data',[DashboardController::class,'fetchWeekData'])->name('dashboard.weekData');
  Route::get('/settings',[AgendaAiSettingsController::class,'index'])->name('settings');
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
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');

    Route::post('/address', [ProfileController::class, 'storeAddress'])->name('address.store');
    Route::put('/address/{address}', [ProfileController::class, 'updateAddress'])->name('address.update');

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

    // Usuários
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


    // Auditoria
    Route::controller(AuditController::class)
        ->middleware(['auth', 'permission:messages-all'])
        ->prefix('audits')
        ->name('audits.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{audits}', 'show')->name('show');
            Route::delete('/{audits}', 'destroy')->name('destroy');
    });


});

Route::controller(AgendaAiClientController::class)
    ->middleware(['auth', 'permission:clients-all'])
    ->prefix('clients')
    ->name('clients.')
    ->group(function () {
        Route::get('/',   'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/',  'store')->name('store');
        Route::get('/{client}/edit', 'edit')->name('edit');
        Route::put('/{client}',      'update')->name('update');
        Route::delete('/{client}',   'destroy')->name('destroy');
});


Route::controller(AgendaAiProfessionalController::class)
    ->middleware(['auth', 'permission:professionals-all'])
    ->prefix('professionals')
    ->name('professionals.')
    ->group(function () {
        Route::get('/',   'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/',  'store')->name('store');
        Route::get('/{professional}/edit', 'edit')->name('edit');
        Route::put('/{professional}',      'update')->name('update');
        Route::delete('/{professional}',   'destroy')->name('destroy');
});

Route::controller(AgendaAiPhoneController::class)
    ->middleware(['auth', 'permission:phones-all'])
    ->prefix('phones')
    ->name('phones.')
    ->group(function () {
        Route::get('/',   'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/',  'store')->name('store');
        Route::get('/{phone}/edit', 'edit')->name('edit');
        Route::put('/{phone}',      'update')->name('update');
        Route::delete('/{phone}',   'destroy')->name('destroy');
});


Route::controller(AgendaAiAddressEstablishmentController::class)
    ->middleware(['auth', 'permission:addresses-all'])
    ->prefix('addresses')
    ->name('addresses.')
    ->group(function () {
        Route::get('/',   'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/',  'store')->name('store');
        Route::get('/{address}/edit', 'edit')->name('edit');
        Route::put('/{address}',      'update')->name('update');
        Route::delete('/{address}',   'destroy')->name('destroy');
});

Route::controller(AgendaAiServiceController::class)
    ->middleware(['auth', 'permission:services-all'])
    ->prefix('services')
    ->name('services.')
    ->group(function () {
        Route::get('/',   'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/',  'store')->name('store');
        Route::get('/{service}/edit', 'edit')->name('edit');
        Route::put('/{service}',      'update')->name('update');
        Route::delete('/{service}',   'destroy')->name('destroy');
});


Route::controller(AgendaAiScheduleController::class)
    ->middleware(['auth', 'permission:schedules-all'])
    ->prefix('schedules')
    ->name('schedules.')
    ->group(function () {
        Route::get('/',   'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/',  'store')->name('store');
        Route::get('/{schedule}/edit', 'edit')->name('edit');
        Route::put('/{schedule}',      'update')->name('update');
        Route::delete('/{schedule}',   'destroy')->name('destroy');
});

Route::controller(AgendaAiScheduleAvailabilityController::class)
    ->middleware(['auth', 'permission:schedule-availabilities-all'])
    ->prefix('schedule-availabilities')
    ->name('schedule-availabilities.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{availability}/edit', 'edit')->name('edit');
        Route::put('/{availability}', 'update')->name('update');
        Route::delete('/{availability}', 'destroy')->name('destroy');
});

Route::controller(AgendaAiAppointmentController::class)
    ->middleware(['auth', 'permission:appointments-all'])
    ->prefix('appointments')
    ->name('appointments.')
    ->group(function () {
        Route::get('/',   'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/',  'store')->name('store');
        Route::get('/{appointment}/edit', 'edit')->name('edit');
        Route::put('/{appointment}',      'update')->name('update');
        Route::delete('/{appointment}',   'destroy')->name('destroy');
});
// em vez de chamar index(), chame history()
Route::middleware(['auth','permission:appointments-history-all'])
    ->get('/appointments/history', [AgendaAiAppointmentController::class,'history'])
    ->name('appointments.history');

Route::middleware(['auth','permission:appointments-all'])
    ->prefix('appointments')
    ->name('appointments.')
    ->group(function(){
        Route::get('review/{appointment}', [ServiceReviewController::class,'create'])
            ->name('review.create');
        Route::post('review/{appointment}', [ServiceReviewController::class,'store'])
            ->name('review.store');
    });


Route::controller(AgendaAiRequestController::class)
    ->middleware(['auth', 'permission:requests-all'])
    ->prefix('requests')
    ->name('requests.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{request}/edit', 'edit')->name('edit');
        Route::put('/{request}', 'update')->name('update');
        Route::delete('/{request}', 'destroy')->name('destroy');
});

Route::get('/requests/received', [AgendaAiRequestController::class, 'received'])
    ->middleware(['auth', 'permission:requests-received-all'])
    ->name('requests.received');

Route::patch('/requests/{request}/status', [AgendaAiRequestController::class, 'updateStatus'])
    ->middleware(['auth', 'permission:requests-received-all'])
    ->name('requests.updateStatus');


Route::controller(AgendaAiMessageController::class)
    ->middleware(['auth', 'permission:messages-all'])
    ->prefix('messages')
    ->name('messages.')
    ->group(function () {
        Route::get('/',   'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/',  'store')->name('store');
        Route::get('/{message}/edit', 'edit')->name('edit');
        Route::put('/{message}',      'update')->name('update');
        Route::delete('/{message}',   'destroy')->name('destroy');
        Route::post('bulk-update', 'bulkUpdate')->name('messages.bulk-update');


});

Route::middleware(['auth', 'permission:mensagens-settings-all'])->group(function () {
    Route::get('/messages/settings', [AgendaAiMessageSettingController::class, 'index'])->name('messages.settings');
    Route::post('/messages/settings', [AgendaAiMessageSettingController::class, 'update'])->name('messages.settings.update');
    Route::get('/settings/chat-link', [AgendaAiChatLinkController::class, 'edit'])->name('settings.chat-link.edit');
    Route::post('/settings/chat-link', [AgendaAiChatLinkController::class, 'update'])->name('settings.chat-link.update');
});



Route::controller(AgendaAiPaymentController::class)
    ->middleware(['auth', 'permission:signature-all'])
    ->prefix('payments')
    ->name('payments.')
    ->group(function () {
        Route::get('/',   'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/',  'store')->name('store');
        Route::get('/{payment}/edit', 'edit')->name('edit');
        Route::put('/{payment}',      'update')->name('update');
        Route::delete('/{payment}',   'destroy')->name('destroy');
        Route::get('payments/list', [AgendaAiPaymentController::class, 'listPayments'])->name('payments.list');
        Route::get('payments/generate/{plano}', [AgendaAiPaymentController::class, 'generatePayment'])->name('payments.generate');
        Route::get('plans/customer', [AgendaAiPlanController::class, 'indexCustomer'])->name('plans.customer');
    });

// Configurações e Relatórios
Route::middleware(['auth','can:configs-all'])->group(function () {
    Route::resource('config', AppConfigController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('relatorios', ReportController::class);

    Route::post('relatorios/preview', [ReportController::class, 'previewReport'])->name('relatorios.preview');
    Route::post('relatorios/related-tables', [ReportController::class, 'listaTabelasRelacionadas'])->name('relatorios.relatedTables');
    Route::post('relatorios/columns-fk', [ReportController::class, 'listaColunasFK'])->name('relatorios.columnsFk');
    Route::post('relatorios/columns-pk', [ReportController::class, 'listaColunasPK'])->name('relatorios.columnsPk');
    Route::post('relatorios/columns', [ReportController::class, 'listaColunas'])->name('relatorios.columns');
    Route::get('relatorios/render/{reportUuid}', [ReportController::class, 'executeReport'])->name('relatorios.renderReport');

    Route::get('notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    Route::middleware('permission:notification-all')->group(function () {
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
        Route::post('notifications/send', [NotificationController::class, 'store'])->name('notifications.send');
    });

    Route::post('push-subscriptions', [PushSubscriptionController::class, 'store'])->name('push-subscriptions.store');
});


// Rotas restritas a master
Route::middleware(['auth', 'restrict.system.access'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('plans', AgendaAiPlanController::class);
    Route::resource('menus', MenuSideBarController::class);
    Route::get('audits', [AuditController::class, 'index'])->name('audits.index');

});

Route::get('/public/{establishment:uuid}/services', [\App\Http\Controllers\PublicChatController::class, 'services']);
Route::get('/public/{establishment:uuid}/messages', [\App\Http\Controllers\PublicChatController::class, 'messages']);
Route::get('/{slug}', [\App\Http\Controllers\PublicChatController::class, 'show'])->name('chat.show');

require __DIR__ . '/auth.php';
