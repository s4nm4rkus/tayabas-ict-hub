<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AuditTrailController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\AO\AOLeaveController;
use App\Http\Controllers\ASDS\ASDSLeaveController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Head\HeadLeaveController;
use App\Http\Controllers\HR\CertRequestController;
use App\Http\Controllers\HR\HRLeaveController;
use App\Http\Controllers\Shared\BoardController;
use App\Http\Controllers\Shared\MessageController;
use App\Http\Controllers\Shared\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/units/personnel', fn () => view('units.personnel'))->name('unit.personnel');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
});

Route::get('/2fa', [TwoFactorController::class, 'show'])->name('2fa.show');
Route::post('/2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'update'])->name('password.update');

    // ── Admin ─────────────────────────────────────────────────────────────────
    Route::middleware('role:Super Administrator')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('employees/import/form', [EmployeeController::class, 'importForm'])->name('admin.employees.import.form');
        Route::post('employees/import', [EmployeeController::class, 'import'])->name('admin.employees.import');
        Route::get('employees/import/template', [EmployeeController::class, 'importTemplate'])->name('admin.employees.import.template');
        Route::get('employees/export/csv', [EmployeeController::class, 'exportCsv'])->name('admin.employees.export.csv');
        Route::get('employees/export/pdf', [EmployeeController::class, 'exportPdf'])->name('admin.employees.export.pdf');
        Route::post('employees/{id}/service', [EmployeeController::class, 'storeServiceRecord'])->name('admin.employees.service.store');
        Route::resource('employees', EmployeeController::class)->names('admin.employees');

        Route::get('roles', [RoleController::class, 'index'])->name('admin.roles.index');
        Route::post('roles', [RoleController::class, 'store'])->name('admin.roles.store');
        Route::put('roles/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

        Route::get('salary', [SalaryController::class, 'index'])->name('admin.salary.index');
        Route::post('salary', [SalaryController::class, 'store'])->name('admin.salary.store');
        Route::put('salary/{id}', [SalaryController::class, 'update'])->name('admin.salary.update');
        Route::delete('salary/{id}', [SalaryController::class, 'destroy'])->name('admin.salary.destroy');

        Route::get('attendance', [AttendanceController::class, 'index'])->name('admin.attendance.index');
        Route::post('attendance', [AttendanceController::class, 'store'])->name('admin.attendance.store');
        Route::delete('attendance/{id}', [AttendanceController::class, 'destroy'])->name('admin.attendance.destroy');

        Route::get('messages', [MessageController::class, 'index'])->name('admin.messages.index');
        Route::post('messages', [MessageController::class, 'store'])->name('admin.messages.store');
        Route::delete('messages/{id}', [MessageController::class, 'destroy'])->name('admin.messages.destroy');

        Route::get('board', [BoardController::class, 'index'])->name('admin.board.index');
        Route::post('board', [BoardController::class, 'store'])->name('admin.board.store');
        Route::delete('board/{id}', [BoardController::class, 'destroy'])->name('admin.board.destroy');

        Route::get('audit', [AuditTrailController::class, 'index'])->name('admin.audit.index');
        Route::get('leaves', [LeaveController::class, 'index'])->name('admin.leaves.index');

        Route::get('backup', [BackupController::class, 'index'])->name('admin.backup.index');
        Route::get('backup/download', [BackupController::class, 'download'])->name('admin.backup.download');

        Route::get('profile', [ProfileController::class, 'show'])->name('admin.profile.show');
        Route::post('profile/photo', [ProfileController::class, 'updatePhoto'])->name('admin.profile.photo');
        Route::post('profile/password', [ProfileController::class, 'changePassword'])->name('admin.profile.password');
        Route::post('profile/signature', [ProfileController::class, 'updateSignature'])->name('admin.profile.signature');

    });

    // ── HR ────────────────────────────────────────────────────────────────────
    Route::middleware('role:HR')->prefix('hr')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\HR\DashboardController::class, 'index'])->name('hr.dashboard');

        // Leave — specific routes BEFORE wildcard {id}
        Route::get('/leave', [HRLeaveController::class, 'index'])->name('hr.leave.index');
        Route::get('/leave/{id}', [HRLeaveController::class, 'show'])->name('hr.leave.show');
        Route::put('/leave/{id}/approve', [HRLeaveController::class, 'approve'])->name('hr.leave.approve');
        Route::put('/leave/{id}/decline', [HRLeaveController::class, 'decline'])->name('hr.leave.decline');
        Route::get('/leave/{id}/print', [HRLeaveController::class, 'print'])->name('hr.leave.print');
        Route::get('/leave/{id}/pdf', [HRLeaveController::class, 'pdf'])->name('hr.leave.pdf');

        // Certificates
        Route::get('/certificates', [CertRequestController::class, 'index'])->name('hr.certificates.index');
        Route::put('/certificates/{id}/accept', [CertRequestController::class, 'accept'])->name('hr.certificates.accept');
        Route::put('/certificates/{id}/decline', [CertRequestController::class, 'decline'])->name('hr.certificates.decline');
        Route::get('/certificates/{id}/pdf', [CertRequestController::class, 'generatePdf'])->name('hr.certificates.pdf');

        // Employees — specific routes BEFORE resource
        Route::get('/employees/import/form', [App\Http\Controllers\HR\EmployeeController::class, 'importForm'])->name('hr.employees.import.form');
        Route::post('/employees/import', [App\Http\Controllers\HR\EmployeeController::class, 'import'])->name('hr.employees.import');
        Route::get('/employees/import/template', [App\Http\Controllers\HR\EmployeeController::class, 'importTemplate'])->name('hr.employees.import.template');
        Route::get('/employees/create', [App\Http\Controllers\HR\EmployeeController::class, 'create'])->name('hr.employees.create');
        Route::get('/employees/export/csv', [App\Http\Controllers\HR\EmployeeController::class, 'exportCsv'])->name('hr.employees.export.csv');
        Route::get('/employees/export/pdf', [App\Http\Controllers\HR\EmployeeController::class, 'exportPdf'])->name('hr.employees.export.pdf');
        Route::get('/employees', [App\Http\Controllers\HR\EmployeeController::class, 'index'])->name('hr.employees.index');
        Route::get('/employees/{id}', [App\Http\Controllers\HR\EmployeeController::class, 'show'])->name('hr.employees.show');
        Route::post('/employees', [App\Http\Controllers\HR\EmployeeController::class, 'store'])->name('hr.employees.store');
        Route::get('/employees/{id}/edit', [App\Http\Controllers\HR\EmployeeController::class, 'edit'])->name('hr.employees.edit');
        Route::put('/employees/{id}', [App\Http\Controllers\HR\EmployeeController::class, 'update'])->name('hr.employees.update');
        Route::post('/employees/{id}/service', [App\Http\Controllers\HR\EmployeeController::class, 'storeServiceRecord'])->name('hr.employees.service.store');


        // Attendance — specific routes BEFORE wildcard
        Route::get('/attendance/export/csv', [App\Http\Controllers\HR\AttendanceController::class, 'exportCsv'])->name('hr.attendance.export.csv');
        Route::get('/attendance/import', [App\Http\Controllers\HR\AttendanceController::class, 'importForm'])->name('hr.attendance.import.form');
        Route::post('/attendance/import', [App\Http\Controllers\HR\AttendanceController::class, 'import'])->name('hr.attendance.import');
        Route::get('/attendance/template', [App\Http\Controllers\HR\AttendanceController::class, 'downloadTemplate'])->name('hr.attendance.template');
        Route::get('/attendance', [App\Http\Controllers\HR\AttendanceController::class, 'index'])->name('hr.attendance.index');
        Route::post('/attendance', [App\Http\Controllers\HR\AttendanceController::class, 'store'])->name('hr.attendance.store');
        Route::delete('/attendance/{id}', [App\Http\Controllers\HR\AttendanceController::class, 'destroy'])->name('hr.attendance.destroy');

        Route::get('/messages', [MessageController::class, 'index'])->name('hr.messages.index');
        Route::post('/messages', [MessageController::class, 'store'])->name('hr.messages.store');
        Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('hr.messages.destroy');

        Route::get('/board', [BoardController::class, 'index'])->name('hr.board.index');
        Route::post('/board', [BoardController::class, 'store'])->name('hr.board.store');
        Route::delete('/board/{id}', [BoardController::class, 'destroy'])->name('hr.board.destroy');

        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('hr.profile.show');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('hr.profile.photo');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('hr.profile.password');
        Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])->name('hr.profile.signature');
    });

    // ── Department Head ───────────────────────────────────────────────────────
    Route::middleware('role:Department Head')->prefix('head')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Head\DashboardController::class, 'index'])->name('head.dashboard');

        Route::get('/leave', [HeadLeaveController::class, 'index'])->name('head.leave.index');
        Route::get('/leave/{id}', [HeadLeaveController::class, 'show'])->name('head.leave.show');
        Route::put('/leave/{id}/approve', [HeadLeaveController::class, 'approve'])->name('head.leave.approve');
        Route::put('/leave/{id}/decline', [HeadLeaveController::class, 'decline'])->name('head.leave.decline');

        Route::get('/messages', [MessageController::class, 'index'])->name('head.messages.index');
        Route::post('/messages', [MessageController::class, 'store'])->name('head.messages.store');
        Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('head.messages.destroy');

        Route::get('/board', [BoardController::class, 'index'])->name('head.board.index');

        Route::get('/profile', [ProfileController::class, 'show'])->name('head.profile.show');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('head.profile.photo');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('head.profile.password');
        Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])->name('head.profile.signature');
    });

    // ── AO (Administrative Officer) ───────────────────────────────────────────
    Route::middleware('role:Administrative Officer')->prefix('ao')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AO\DashboardController::class, 'index'])->name('ao.dashboard');

        Route::get('/leave', [AOLeaveController::class, 'index'])->name('ao.leave.index');
        Route::get('/leave/{id}', [AOLeaveController::class, 'show'])->name('ao.leave.show');
        Route::put('/leave/{id}/approve', [AOLeaveController::class, 'approve'])->name('ao.leave.approve');
        Route::put('/leave/{id}/decline', [AOLeaveController::class, 'decline'])->name('ao.leave.decline');

        Route::get('/profile', [ProfileController::class, 'show'])->name('ao.profile.show');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('ao.profile.photo');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('ao.profile.password');
        Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])->name('ao.profile.signature');
    });

    // ── ASDS ──────────────────────────────────────────────────────────────────
    Route::middleware('role:ASDS')->prefix('asds')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\ASDS\DashboardController::class, 'index'])->name('asds.dashboard');

        Route::get('/leave', [ASDSLeaveController::class, 'index'])->name('asds.leave.index');
        Route::get('/leave/{id}', [ASDSLeaveController::class, 'show'])->name('asds.leave.show');
        Route::put('/leave/{id}/approve', [ASDSLeaveController::class, 'approve'])->name('asds.leave.approve');
        Route::put('/leave/{id}/decline', [ASDSLeaveController::class, 'decline'])->name('asds.leave.decline');

        Route::get('/profile', [ProfileController::class, 'show'])->name('asds.profile.show');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('asds.profile.photo');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('asds.profile.password');
        Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])->name('asds.profile.signature');
    });

    // ── Employee ──────────────────────────────────────────────────────────────
    // NOTE: All specific/named routes MUST come BEFORE wildcard {id} routes
    Route::middleware('auth')->prefix('employee')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('employee.dashboard');

        // Leave — CRITICAL: named routes before {id} wildcard
        Route::get('/leave/create', [App\Http\Controllers\Employee\LeaveController::class, 'create'])->name('employee.leave.create');
        Route::get('/leave', [App\Http\Controllers\Employee\LeaveController::class, 'index'])->name('employee.leave.index');
        Route::post('/leave', [App\Http\Controllers\Employee\LeaveController::class, 'store'])->name('employee.leave.store');
        Route::put('/leave/{id}/cancel', [App\Http\Controllers\Employee\LeaveController::class, 'cancel'])->name('employee.leave.cancel');
        Route::get('/leave/{id}/print', [App\Http\Controllers\Employee\LeaveController::class, 'print'])->name('employee.leave.print');
        Route::get('/leave/{id}/pdf', [App\Http\Controllers\Employee\LeaveController::class, 'downloadPdf'])->name('employee.leave.pdf');
        Route::get('/leave/{id}', [App\Http\Controllers\Employee\LeaveController::class, 'show'])->name('employee.leave.show');

        // Certificates
        Route::get('/certificates', [App\Http\Controllers\Employee\CertRequestController::class, 'index'])->name('employee.certificates.index');
        Route::post('/certificates', [App\Http\Controllers\Employee\CertRequestController::class, 'store'])->name('employee.certificates.store');

        // Attendance
        Route::get('/attendance', [App\Http\Controllers\Employee\AttendanceController::class, 'index'])->name('employee.attendance.index');

        Route::get('/messages', [MessageController::class, 'index'])->name('employee.messages.index');
        Route::post('/messages', [MessageController::class, 'store'])->name('employee.messages.store');
        Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('employee.messages.destroy');

        Route::get('/board', [BoardController::class, 'index'])->name('employee.board.index');

        // Profile — was missing photo and password routes!
        Route::get('/profile', [ProfileController::class, 'show'])->name('employee.profile.show');
        Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])->name('employee.profile.signature');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('employee.profile.photo');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('employee.profile.password');
    });

});
