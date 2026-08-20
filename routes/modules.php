<?php

use App\Http\Controllers\Modules\CashCategory\CashCategoryController;
use App\Http\Controllers\Modules\CashTransaction\CashTransactionController;
use App\Http\Controllers\Modules\Dashboard\DashboardController;
use App\Http\Controllers\Modules\Profile\ProfileController;
use App\Http\Controllers\Modules\Report\CashReportController;
use App\Http\Controllers\Modules\User\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active.user'])->name('modules.')->group(function () {
    // Dashboard Module
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cash Transactions & Categories Module
    Route::prefix('cash')->name('cash.')->group(function () {
        Route::resource('transactions', CashTransactionController::class);
        Route::resource('categories', CashCategoryController::class);
    });

    // Reports Module
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/cash', [CashReportController::class, 'index'])->name('cash.index');
        Route::get('/cash/print', [CashReportController::class, 'print'])->name('cash.print');
    });

    // User Management Module (Admin only)
    Route::resource('users', UserController::class)->middleware('admin');

    // Profile Module
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'changePassword'])->name('password');
    });
});
