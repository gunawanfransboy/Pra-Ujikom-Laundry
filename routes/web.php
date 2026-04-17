<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TypeOfServiceController;
use App\Http\Controllers\TransOrderController;
use App\Http\Controllers\TransLaundryPickupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard accessible by all logged in users
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('services', TypeOfServiceController::class)->parameters(['services' => 'service'])->except(['show']);
        
        // Vouchers fully managed by Admin
        Route::resource('vouchers', \App\Http\Controllers\VoucherController::class)->except(['show']);
    });

    // Operator & Admin Routes (Also Pimpinan for some)
    Route::middleware('role:operator,admin,pimpinan')->group(function () {
        Route::resource('orders', TransOrderController::class);
        Route::patch('/orders/{order}/status', [TransOrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/api/check-member-first-order/{customer}', [TransOrderController::class, 'checkMemberFirstOrder'])->name('orders.checkMember');
    });

    Route::middleware('role:operator,admin')->group(function () {
        Route::resource('customers', CustomerController::class)->except(['show']);
        // API Check Voucher accessible by operator during order creation
        Route::post('/api/check-voucher', [\App\Http\Controllers\VoucherController::class, 'checkVoucher'])->name('vouchers.check');
    });

    // Pimpinan Routes
    Route::middleware('role:pimpinan,admin')->group(function () {
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    });
});
