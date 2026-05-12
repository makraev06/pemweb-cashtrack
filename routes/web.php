<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');
    Route::get('/transactions/export', [TransactionController::class, 'export'])
        ->name('transactions.export');
    Route::get('/transactions/create', [TransactionController::class, 'create'])
        ->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])
        ->name('transactions.store');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])
        ->name('transactions.destroy');

    Route::get('/chart', [ChartController::class, 'index'])
        ->name('chart.index');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::get('/api/notifications', [NotificationController::class, 'api'])
        ->name('api.notifications');

    Route::get('/calendar', [CalendarController::class, 'index'])
        ->name('calendar.index');

    Route::get('/accounts', [AccountController::class, 'index'])
        ->name('accounts.index');
    Route::post('/accounts', [AccountController::class, 'store'])
        ->name('accounts.store');
    Route::put('/accounts/{account}', [AccountController::class, 'update'])
        ->name('accounts.update');
    Route::delete('/accounts/{account}', [AccountController::class, 'destroy'])
        ->name('accounts.destroy');

    Route::resource('assets', AssetController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';