<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkSessionController;
use App\Http\Controllers\Admin\AIPAdresseIpController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'check.login.token'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/pointage', [DashboardController::class, 'pointage'])
        ->name('pointage');

    Route::post('/session/start', [DashboardController::class, 'startSession'])
        ->name('session.start');

    Route::post('/session/close', [DashboardController::class, 'closeSession'])
        ->name('session.close');
});
Route::middleware(['check.login.token', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        //Route::resource('users', UserController::class);
        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('admin.users.create');

        Route::post('/users/store', [UserController::class, 'store'])
            ->name('admin.users.store');

        Route::get('/users/{id}/edit', [UserController::class, 'edit'])
            ->name('admin.users.edit');

        Route::put('/users/{id}/update', [UserController::class, 'update'])
            ->name('admin.users.update');

        Route::delete('/users/{id}/destroy', [UserController::class, 'destroy'])
            ->name('admin.users.destroy');

        Route::get('/sessions', [WorkSessionController::class, 'index'])
            ->name('admin.sessions.index');

        Route::get('/sessions/create', [WorkSessionController::class, 'create'])
            ->name('admin.sessions.create');

        Route::get('/sessions/{id}/edit', [WorkSessionController::class, 'edit'])
            ->name('admin.sessions.edit');

        Route::put('/sessions/{id}/update', [WorkSessionController::class, 'update'])
            ->name('admin.sessions.update');

        Route::post('/sessions/{id}/close', [WorkSessionController::class, 'forceClose'])
            ->name('admin.sessions.close');

        Route::post('/sessions/store', [WorkSessionController::class, 'store'])
            ->name('admin.sessions.store');

        Route::delete('/sessions/{id}/destroy', [WorkSessionController::class, 'destroy'])
            ->name('admin.sessions.destroy');

        Route::get('sessions/crosstable', [WorkSessionController::class, 'crossTable'])
            ->name('admin.sessions.crosstable');

        // IP Management
        Route::get('/ips', [AIPAdresseIpController::class, 'index'])->name('admin.ips.index');
        Route::post('/ips/store-current', [AIPAdresseIpController::class, 'storeCurrent'])->name('admin.ips.store_current');
        Route::get('/ips/create', [AIPAdresseIpController::class, 'create'])->name('admin.ips.create');
        Route::post('/ips/store', [AIPAdresseIpController::class, 'store'])->name('admin.ips.store');
        Route::get('/ips/{id}/edit', [AIPAdresseIpController::class, 'edit'])->name('admin.ips.edit');
        Route::put('/ips/{id}/update', [AIPAdresseIpController::class, 'update'])->name('admin.ips.update');
        Route::delete('/ips/{id}/destroy', [AIPAdresseIpController::class, 'destroy'])->name('admin.ips.destroy');
    });
