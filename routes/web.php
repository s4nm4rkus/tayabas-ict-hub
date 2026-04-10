<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\PasswordController;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
});



// 2FA — needs session but no auth
Route::get('/2fa', [TwoFactorController::class, 'show'])->name('2fa.show');
Route::post('/2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'update'])->name('password.update');
    

    Route::middleware('role:Super Administrator')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::get('employees/import/form', [\App\Http\Controllers\Admin\EmployeeController::class, 'importForm'])
            ->name('admin.employees.import.form');
        Route::post('employees/import', [\App\Http\Controllers\Admin\EmployeeController::class, 'import'])
            ->name('admin.employees.import');
        Route::get('employees/import/template', [\App\Http\Controllers\Admin\EmployeeController::class, 'importTemplate'])
            ->name('admin.employees.import.template');
        Route::get('employees/export/csv', [\App\Http\Controllers\Admin\EmployeeController::class, 'exportCsv'])
            ->name('admin.employees.export.csv');
        Route::get('employees/export/pdf', [\App\Http\Controllers\Admin\EmployeeController::class, 'exportPdf'])
            ->name('admin.employees.export.pdf');
        Route::post('employees/{id}/service', [\App\Http\Controllers\Admin\EmployeeController::class, 'storeServiceRecord'])
            ->name('admin.employees.service.store');
        Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class)
            ->names('admin.employees');

        // Roles management
        Route::get('roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])
            ->name('admin.roles.index');
        Route::post('roles', [\App\Http\Controllers\Admin\RoleController::class, 'store'])
            ->name('admin.roles.store');
        Route::put('roles/{id}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])
            ->name('admin.roles.update');
        Route::delete('roles/{id}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])
            ->name('admin.roles.destroy');
    });



    Route::middleware('role:HR')->prefix('hr')->group(function () {
        Route::get('/dashboard', function () {
            return 'HR Dashboard — coming soon';
        })->name('hr.dashboard');
    });

    Route::middleware('role:Department Head')->prefix('head')->group(function () {
        Route::get('/dashboard', function () {
            return 'Head Dashboard — coming soon';
        })->name('head.dashboard');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/dashboard', function () {
            return 'Employee Dashboard — coming soon';
        })->name('employee.dashboard');
    });
});