<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\PasswordController;
use App\Models\SubPosition;

// use Illuminate\Support\Facades\Auth;

// Landing page — public
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
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

        // Admin Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('admin.dashboard');
        
        // Employees management
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
            
        // Salary Grade management
        Route::get('salary', [\App\Http\Controllers\Admin\SalaryController::class, 'index'])
            ->name('admin.salary.index');
        Route::post('salary', [\App\Http\Controllers\Admin\SalaryController::class, 'store'])
            ->name('admin.salary.store');
        Route::put('salary/{id}', [\App\Http\Controllers\Admin\SalaryController::class, 'update'])
            ->name('admin.salary.update');
        Route::delete('salary/{id}', [\App\Http\Controllers\Admin\SalaryController::class, 'destroy'])
            ->name('admin.salary.destroy');

        // Attendance management
        Route::get('attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])
            ->name('admin.attendance.index');
        Route::post('attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'store'])
            ->name('admin.attendance.store');
        Route::delete('attendance/{id}', [\App\Http\Controllers\Admin\AttendanceController::class, 'destroy'])
            ->name('admin.attendance.destroy');

        // Messages
        Route::get('messages', [\App\Http\Controllers\Shared\MessageController::class, 'index'])
            ->name('admin.messages.index');
        Route::post('messages', [\App\Http\Controllers\Shared\MessageController::class, 'store'])
            ->name('admin.messages.store');
        Route::delete('messages/{id}', [\App\Http\Controllers\Shared\MessageController::class, 'destroy'])
            ->name('admin.messages.destroy');

        // Notice Board
        Route::get('board', [\App\Http\Controllers\Shared\BoardController::class, 'index'])
            ->name('admin.board.index');
        Route::post('board', [\App\Http\Controllers\Shared\BoardController::class, 'store'])
            ->name('admin.board.store');
        Route::delete('board/{id}', [\App\Http\Controllers\Shared\BoardController::class, 'destroy'])
            ->name('admin.board.destroy');

        // Audit Trail
        Route::get('audit', [\App\Http\Controllers\Admin\AuditTrailController::class, 'index'])
            ->name('admin.audit.index');

        // Profile
        Route::get('profile', [\App\Http\Controllers\Shared\ProfileController::class, 'show'])
            ->name('admin.profile.show');
        Route::post('profile/photo', [\App\Http\Controllers\Shared\ProfileController::class, 'updatePhoto'])
            ->name('admin.profile.photo');
        Route::post('profile/password', [\App\Http\Controllers\Shared\ProfileController::class, 'changePassword'])
            ->name('admin.profile.password');

        // Leave Requests
        Route::get('leaves', [\App\Http\Controllers\Admin\LeaveController::class, 'index'])
            ->name('admin.leaves.index');

        // Backup
        Route::get('backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])
            ->name('admin.backup.index');
        Route::get('backup/download', [\App\Http\Controllers\Admin\BackupController::class, 'download'])
            ->name('admin.backup.download');

    });
         
   // ── HR ───────────────────────────────────────────────────────────────────
    Route::middleware(['auth', 'role:HR'])->prefix('hr')->group(function () {

        // HR Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\HR\DashboardController::class, 'index'])->name('hr.dashboard');
        
        // Leave approvals
        Route::get('/leave', [\App\Http\Controllers\HR\HRLeaveController::class, 'index'])->name('hr.leave.index');
        Route::put('/leave/{id}/approve', [\App\Http\Controllers\HR\HRLeaveController::class, 'approve'])->name('hr.leave.approve');
        Route::put('/leave/{id}/decline', [\App\Http\Controllers\HR\HRLeaveController::class, 'decline'])->name('hr.leave.decline');

        // Certificate requests
        Route::get('/certificates', [\App\Http\Controllers\HR\CertRequestController::class, 'index'])->name('hr.certificates.index');
        Route::put('/certificates/{id}/accept', [\App\Http\Controllers\HR\CertRequestController::class, 'accept'])->name('hr.certificates.accept');
        Route::put('/certificates/{id}/decline', [\App\Http\Controllers\HR\CertRequestController::class, 'decline'])->name('hr.certificates.decline');
        Route::get('/certificates/{id}/pdf', [\App\Http\Controllers\HR\CertRequestController::class, 'generatePdf'])->name('hr.certificates.pdf');

        // HR Employees
        Route::get('/employees', [\App\Http\Controllers\HR\EmployeeController::class, 'index'])->name('hr.employees.index');
        Route::get('/employees/export/csv', [\App\Http\Controllers\HR\EmployeeController::class, 'exportCsv'])->name('hr.employees.export.csv');
        Route::get('/employees/export/pdf', [\App\Http\Controllers\HR\EmployeeController::class, 'exportPdf'])->name('hr.employees.export.pdf');
        Route::get('/employees/{id}', [\App\Http\Controllers\HR\EmployeeController::class, 'show'])->name('hr.employees.show');   
        
        // HR Attendance
        Route::get('/attendance', [\App\Http\Controllers\HR\AttendanceController::class, 'index'])->name('hr.attendance.index');
        Route::post('/attendance', [\App\Http\Controllers\HR\AttendanceController::class, 'store'])->name('hr.attendance.store');
        Route::delete('/attendance/{id}', [\App\Http\Controllers\HR\AttendanceController::class, 'destroy'])->name('hr.attendance.destroy');
        Route::get('/attendance/export/csv', [\App\Http\Controllers\HR\AttendanceController::class, 'exportCsv'])->name('hr.attendance.export.csv');
        Route::get('/attendance/import', [\App\Http\Controllers\HR\AttendanceController::class, 'importForm'])->name('hr.attendance.import.form');
        Route::post('/attendance/import', [\App\Http\Controllers\HR\AttendanceController::class, 'import'])->name('hr.attendance.import');
        Route::get('/attendance/template', [\App\Http\Controllers\HR\AttendanceController::class, 'downloadTemplate'])->name('hr.attendance.template');

        // Messages
        Route::get('/messages', [\App\Http\Controllers\Shared\MessageController::class, 'index'])->name('hr.messages.index');
        Route::post('/messages', [\App\Http\Controllers\Shared\MessageController::class, 'store'])->name('hr.messages.store');
        Route::delete('/messages/{id}', [\App\Http\Controllers\Shared\MessageController::class, 'destroy'])->name('hr.messages.destroy');

        // Notice Board
        Route::get('/board', [\App\Http\Controllers\Shared\BoardController::class, 'index'])->name('hr.board.index');
        Route::post('/board', [\App\Http\Controllers\Shared\BoardController::class, 'store'])->name('hr.board.store');
        Route::delete('/board/{id}', [\App\Http\Controllers\Shared\BoardController::class, 'destroy'])->name('hr.board.destroy');

        // Profile
        Route::get('/profile', [\App\Http\Controllers\Shared\ProfileController::class, 'show'])->name('hr.profile.show');
        Route::post('/profile/photo', [\App\Http\Controllers\Shared\ProfileController::class, 'updatePhoto'])->name('hr.profile.photo');
        Route::post('/profile/password', [\App\Http\Controllers\Shared\ProfileController::class, 'changePassword'])->name('hr.profile.password');

    });
 
    // ── Department Head ──────────────────────────────────────────────────────
    Route::middleware(['auth', 'role:Department Head'])->prefix('head')->group(function () {
        
        // Head dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Head\DashboardController::class, 'index'])->name('head.dashboard');
        // Leave approvals
        Route::get('/leave', [\App\Http\Controllers\Head\HeadLeaveController::class, 'index'])->name('head.leave.index');
        Route::put('/leave/{id}/approve', [\App\Http\Controllers\Head\HeadLeaveController::class, 'approve'])->name('head.leave.approve');
        Route::put('/leave/{id}/decline', [\App\Http\Controllers\Head\HeadLeaveController::class, 'decline'])->name('head.leave.decline');

        // Messages    
        Route::get('/messages', [\App\Http\Controllers\Shared\MessageController::class, 'index'])->name('head.messages.index');
        Route::post('/messages', [\App\Http\Controllers\Shared\MessageController::class, 'store'])->name('head.messages.store');
        Route::delete('/messages/{id}', [\App\Http\Controllers\Shared\MessageController::class, 'destroy'])->name('head.messages.destroy');

        // Notice Board
        Route::get('/board', [\App\Http\Controllers\Shared\BoardController::class, 'index'])->name('head.board.index');


        // Profile
        Route::get('/profile', [\App\Http\Controllers\Shared\ProfileController::class, 'show'])->name('head.profile.show');
        Route::post('/profile/photo', [\App\Http\Controllers\Shared\ProfileController::class, 'updatePhoto'])->name('head.profile.photo');
        Route::post('/profile/password', [\App\Http\Controllers\Shared\ProfileController::class, 'changePassword'])->name('head.profile.password');
    });
 
    // ── Employee ─────────────────────────────────────────────────────────────
    Route::middleware('auth')->prefix('employee')->group(function () {
        // Employee dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('employee.dashboard');
    
        //Leave
        Route::get('/leave', [\App\Http\Controllers\Employee\LeaveController::class, 'index'])->name('employee.leave.index');
        Route::get('/leave/create', [\App\Http\Controllers\Employee\LeaveController::class, 'create'])->name('employee.leave.create');
        Route::post('/leave', [\App\Http\Controllers\Employee\LeaveController::class, 'store'])->name('employee.leave.store');
        Route::put('/leave/{id}/cancel', [\App\Http\Controllers\Employee\LeaveController::class, 'cancel'])->name('employee.leave.cancel');

        // Certificate Requests
        Route::get('/certificates', [\App\Http\Controllers\Employee\CertRequestController::class, 'index'])->name('employee.certificates.index');
        Route::post('/certificates', [\App\Http\Controllers\Employee\CertRequestController::class, 'store'])->name('employee.certificates.store');

        // Attendance
        Route::get('/attendance', [\App\Http\Controllers\Employee\AttendanceController::class, 'index'])->name('employee.attendance.index');
        
        // Messages
        Route::get('/messages', [\App\Http\Controllers\Shared\MessageController::class, 'index'])->name('employee.messages.index');
        Route::post('/messages', [\App\Http\Controllers\Shared\MessageController::class, 'store'])->name('employee.messages.store');
        Route::delete('/messages/{id}', [\App\Http\Controllers\Shared\MessageController::class, 'destroy'])->name('employee.messages.destroy');

        // Notice Board
        Route::get('/board', [\App\Http\Controllers\Shared\BoardController::class, 'index'])->name('employee.board.index');

        // Profile
        Route::get('/profile', [\App\Http\Controllers\Shared\ProfileController::class, 'show'])->name('employee.profile.show');
        Route::post('/profile/photo', [\App\Http\Controllers\Shared\ProfileController::class, 'updatePhoto'])->name('employee.profile.photo');
        Route::post('/profile/password', [\App\Http\Controllers\Shared\ProfileController::class, 'changePassword'])->name('employee.profile.password');
    });
 
});