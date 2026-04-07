<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\PasswordController;

// Guest routes (not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
    Route::get('/2fa', [TwoFactorController::class, 'show'])->name('2fa.show');
    Route::post('/2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Force password change
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'update'])->name('password.update');

    // Dashboards (temporary placeholders for now)
    Route::get('/admin/dashboard', function () {
        return 'Super Admin Dashboard — coming soon';
    })->name('admin.dashboard');

    Route::get('/hr/dashboard', function() {
        return 'HR Dashboard — coming soon';
    })->name('hr.dashboard');

    Route::get('/head/dashboard', function () {
        return 'Department Head Dashboard — coming soon';
    })->name('head.dashboard');

    Route::get('/employee/dashboard', function () {
        return 'Employee Dashboard — coming soon';
    })->name('employee.dashboard');
});