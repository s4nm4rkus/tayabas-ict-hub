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
use App\Http\Controllers\HR\AppointmentOptionController;
use App\Http\Controllers\HR\ZktecoImportController;
use App\Http\Controllers\ICT\IctDashboardController;
use App\Http\Controllers\ICT\IctDtsRequestController;
use App\Http\Controllers\ICT\IctEmailRequestController;
use App\Http\Controllers\ICT\IctHelpdeskRequestController;
use App\Http\Controllers\ICT\IctTicketController;
use App\Http\Controllers\Shared\BoardController;
use App\Http\Controllers\Shared\MessageController;
use App\Http\Controllers\Shared\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/units/personnel', fn () => view('units.personnel'))->name('unit.personnel');
Route::get('/units/ict', fn () => view('units.ict'))->name('unit.ict');
Route::get('/ict/forms', fn () => view('ict.request-forms'))->name('ict.forms');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
});

Route::get('/2fa', [TwoFactorController::class, 'show'])->name('2fa.show');
Route::post('/2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');

/*
|--------------------------------------------------------------------------
| ICT — Employee-Facing (Submit Forms, View Own Tickets)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Employee'])->group(function () {
    Route::get('/ict/ta-form', fn () => view('ict.forms.TA-form'))->name('ict.ta-form');
    Route::post('/ict/ta-form', [IctTicketController::class, 'store'])->name('ict.ta-form.store');
    Route::get('/ict/my-tickets', [IctTicketController::class, 'myTickets'])->name('ict.my-tickets');

    Route::get('/ict/email-form', fn () => view('ict.forms.email-form'))->name('ict.email-form');
    Route::post('/ict/email-form', [IctEmailRequestController::class, 'store'])->name('ict.email-form.store');

    Route::get('/ict/dts-form', fn () => view('ict.forms.dts-request'))->name('ict.dts-form');
    Route::post('/ict/dts-form', [IctDtsRequestController::class, 'store'])->name('ict.dts-form.store');

    Route::get('/ict/helpdesk-form', fn () => view('ict.forms.helpdesk-form'))->name('ict.helpdesk-form');
    Route::post('/ict/helpdesk-form', [IctHelpdeskRequestController::class, 'store'])->name('ict.helpdesk-form.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [TwoFactorController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'update'])->name('password.update');

    /*
    |--------------------------------------------------------------------------
    | Super Administrator
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Super Administrator')->prefix('admin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Employees
        Route::get('employees/import/form', [EmployeeController::class, 'importForm'])->name('admin.employees.import.form');
        Route::post('employees/import', [EmployeeController::class, 'import'])->name('admin.employees.import');
        Route::get('employees/import/template', [EmployeeController::class, 'importTemplate'])->name('admin.employees.import.template');
        Route::get('employees/export/csv', [EmployeeController::class, 'exportCsv'])->name('admin.employees.export.csv');
        Route::get('employees/export/pdf', [EmployeeController::class, 'exportPdf'])->name('admin.employees.export.pdf');
        Route::get('employees/bulk-update/form', [EmployeeController::class, 'bulkUpdateForm'])->name('admin.employees.bulk-update.form');
        Route::post('employees/bulk-update', [EmployeeController::class, 'bulkUpdate'])->name('admin.employees.bulk-update');
        Route::get('employees/bulk-update/template', [EmployeeController::class, 'bulkUpdateTemplate'])->name('admin.employees.bulk-update.template');
        Route::post('employees/{id}/service', [EmployeeController::class, 'storeServiceRecord'])->name('admin.employees.service.store');
        Route::resource('employees', EmployeeController::class)->names('admin.employees');

        // Roles
        Route::get('roles', [RoleController::class, 'index'])->name('admin.roles.index');
        Route::post('roles', [RoleController::class, 'store'])->name('admin.roles.store');
        Route::put('roles/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

        // Salary
        Route::get('salary', [SalaryController::class, 'index'])->name('admin.salary.index');
        Route::post('salary', [SalaryController::class, 'store'])->name('admin.salary.store');
        Route::put('salary/{id}', [SalaryController::class, 'update'])->name('admin.salary.update');
        Route::delete('salary/{id}', [SalaryController::class, 'destroy'])->name('admin.salary.destroy');

        // Attendance
        Route::get('attendance', [AttendanceController::class, 'index'])->name('admin.attendance.index');
        Route::post('attendance', [AttendanceController::class, 'store'])->name('admin.attendance.store');
        Route::delete('attendance/{id}', [AttendanceController::class, 'destroy'])->name('admin.attendance.destroy');

        // Messages
        Route::get('messages', [MessageController::class, 'index'])->name('admin.messages.index');
        Route::post('messages', [MessageController::class, 'store'])->name('admin.messages.store');
        Route::delete('messages/{id}', [MessageController::class, 'destroy'])->name('admin.messages.destroy');

        // Board
        Route::get('board', [BoardController::class, 'index'])->name('admin.board.index');
        Route::post('board', [BoardController::class, 'store'])->name('admin.board.store');
        Route::delete('board/{id}', [BoardController::class, 'destroy'])->name('admin.board.destroy');

        // Misc
        Route::get('audit', [AuditTrailController::class, 'index'])->name('admin.audit.index');
        Route::get('leaves', [LeaveController::class, 'index'])->name('admin.leaves.index');
        Route::get('backup', [BackupController::class, 'index'])->name('admin.backup.index');
        Route::get('backup/download', [BackupController::class, 'download'])->name('admin.backup.download');

        // Profile
        Route::get('profile', [ProfileController::class, 'show'])->name('admin.profile.show');
        Route::post('profile/photo', [ProfileController::class, 'updatePhoto'])->name('admin.profile.photo');
        Route::post('profile/password', [ProfileController::class, 'changePassword'])->name('admin.profile.password');
        Route::post('profile/signature', [ProfileController::class, 'updateSignature'])->name('admin.profile.signature');
    });

    /*
    |--------------------------------------------------------------------------
    | ICT Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Super Administrator')
        ->prefix('ict/admin')
        ->name('ict.admin.')
        ->group(function () {

            Route::get('dashboard', [IctDashboardController::class, 'index'])->name('dashboard');

            // Technical Assistance Tickets
            Route::get('tickets', [IctTicketController::class, 'adminIndex'])->name('ta-requests.index');
            Route::get('tickets/{ictTicket}', [IctTicketController::class, 'adminShow'])->name('ta-requests.show');
            Route::put('tickets/{ictTicket}', [IctTicketController::class, 'adminUpdate'])->name('ta-requests.update');
            Route::delete('tickets/{ictTicket}', [IctTicketController::class, 'adminDestroy'])->name('ta-requests.destroy');

            // Email / O365 Requests
            Route::get('email-requests', [IctEmailRequestController::class, 'adminIndex'])->name('email-requests.index');
            Route::get('email-requests/{ictEmailRequest}', [IctEmailRequestController::class, 'adminShow'])->name('email-requests.show');
            Route::put('email-requests/{ictEmailRequest}', [IctEmailRequestController::class, 'adminUpdate'])->name('email-requests.update');
            Route::delete('email-requests/{ictEmailRequest}', [IctEmailRequestController::class, 'adminDestroy'])->name('email-requests.destroy');

            // DTS Requests
            Route::get('dts-requests', [IctDtsRequestController::class, 'adminIndex'])->name('dts-requests.index');
            Route::get('dts-requests/{ictDtsRequest}', [IctDtsRequestController::class, 'adminShow'])->name('dts-requests.show');
            Route::put('dts-requests/{ictDtsRequest}', [IctDtsRequestController::class, 'adminUpdate'])->name('dts-requests.update');
            Route::delete('dts-requests/{ictDtsRequest}', [IctDtsRequestController::class, 'adminDestroy'])->name('dts-requests.destroy');

            // Helpdesk Requests
            Route::get('helpdesk-requests', [IctHelpdeskRequestController::class, 'adminIndex'])->name('helpdesk-requests.index');
            Route::get('helpdesk-requests/{ictHelpdeskRequest}', [IctHelpdeskRequestController::class, 'adminShow'])->name('helpdesk-requests.show');
            Route::put('helpdesk-requests/{ictHelpdeskRequest}', [IctHelpdeskRequestController::class, 'adminUpdate'])->name('helpdesk-requests.update');
            Route::delete('helpdesk-requests/{ictHelpdeskRequest}', [IctHelpdeskRequestController::class, 'adminDestroy'])->name('helpdesk-requests.destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | HR
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:HR')->prefix('hr')->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\HR\DashboardController::class, 'index'])->name('hr.dashboard');

        // Leave
        Route::get('/leave', [HRLeaveController::class, 'index'])->name('hr.leave.index');
        Route::get('/leave/{id}', [HRLeaveController::class, 'show'])->name('hr.leave.show');
        Route::put('/leave/{id}/approve', [HRLeaveController::class, 'approve'])->name('hr.leave.approve');
        Route::put('/leave/{id}/decline', [HRLeaveController::class, 'decline'])->name('hr.leave.decline');
        Route::get('/leave/{id}/print', [HRLeaveController::class, 'print'])->name('hr.leave.print');
        Route::get('/leave/{id}/pdf', [HRLeaveController::class, 'pdf'])->name('hr.leave.pdf');

        // to follow
        // Route::get('/leave/{id}/preview', [HRLeaveController::class, 'preview'])->name('hr.leave.preview');
        // Route::get('/leave/{id}/pdf', [HRLeaveController::class, 'pdf'])->name('hr.leave.pdf');
        // Route::get('/leave/{id}/print', [HRLeaveController::class, 'print'])->name('hr.leave.print');


        // Certificates (remove the duplicate preview route)
        Route::get('/certificates', [CertRequestController::class, 'index'])->name('hr.certificates.index');
        Route::put('/certificates/{id}/accept', [CertRequestController::class, 'accept'])->name('hr.certificates.accept');
        Route::put('/certificates/{id}/decline', [CertRequestController::class, 'decline'])->name('hr.certificates.decline');
        Route::get('/certificates/{id}/preview', [CertRequestController::class, 'preview'])->name('hr.certificates.preview');
        Route::get('/certificates/{id}/pdf', [CertRequestController::class, 'generatePdf'])->name('hr.certificates.pdf');

        // Employees
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
        Route::get('/employees/bulk-update/form', [App\Http\Controllers\HR\EmployeeController::class, 'bulkUpdateForm'])->name('hr.employees.bulk-update.form');
        Route::post('/employees/bulk-update', [App\Http\Controllers\HR\EmployeeController::class, 'bulkUpdate'])->name('hr.employees.bulk-update');
        Route::get('/employees/bulk-update/template', [App\Http\Controllers\HR\EmployeeController::class, 'bulkUpdateTemplate'])->name('hr.employees.bulk-update.template');

        // Employee Service Record
        Route::post('/employees/{userId}/history', [App\Http\Controllers\HR\EmploymentHistoryController::class, 'store'])->name('hr.employees.history.store');
        Route::get('/employees/{userId}/history', [App\Http\Controllers\HR\EmploymentHistoryController::class, 'index'])->name('hr.employees.history.index');
        Route::delete('/employees/{userId}/history/{historyId}', [App\Http\Controllers\HR\EmploymentHistoryController::class, 'destroy'])->name('hr.employees.history.destroy');

        // Attendance
        Route::get('/attendance/export/csv', [App\Http\Controllers\HR\AttendanceController::class, 'exportCsv'])->name('hr.attendance.export.csv');
        Route::get('/attendance', [App\Http\Controllers\HR\AttendanceController::class, 'index'])->name('hr.attendance.index');
        Route::delete('/attendance/{id}', [App\Http\Controllers\HR\AttendanceController::class, 'destroy'])->name('hr.attendance.destroy');
        Route::delete('attendance/reset/month', [App\Http\Controllers\HR\AttendanceController::class, 'resetMonth'])->name('hr.attendance.reset.month');
        Route::delete('attendance/reset/employee', [App\Http\Controllers\HR\AttendanceController::class, 'resetEmployee'])->name('hr.attendance.reset.employee');

        // Roles
        Route::get('/roles', [App\Http\Controllers\HR\RoleController::class, 'index'])->name('hr.roles.index');
        Route::post('/roles', [App\Http\Controllers\HR\RoleController::class, 'store'])->name('hr.roles.store');
        Route::put('/roles/{id}', [App\Http\Controllers\HR\RoleController::class, 'update'])->name('hr.roles.update');
        Route::delete('/roles/{id}', [App\Http\Controllers\HR\RoleController::class, 'destroy'])->name('hr.roles.destroy');

        // Appointment Options
        Route::get('/appointment-options', [AppointmentOptionController::class, 'index'])->name('hr.appointment-options.index');
        Route::post('/appointment-options', [AppointmentOptionController::class, 'store'])->name('hr.appointment-options.store');
        Route::put('/appointment-options/{id}', [AppointmentOptionController::class, 'update'])->name('hr.appointment-options.update');
        Route::patch('/appointment-options/{id}/toggle', [AppointmentOptionController::class, 'toggleActive'])->name('hr.appointment-options.toggle');
        Route::delete('/appointment-options/{id}', [AppointmentOptionController::class, 'destroy'])->name('hr.appointment-options.destroy');

        // ZKTeco Biometric Import
        Route::get('/zkteco/upload', [ZktecoImportController::class, 'showForm'])->name('hr.zkteco.upload');
        Route::post('/zkteco/upload', [ZktecoImportController::class, 'upload'])->name('hr.zkteco.upload.post');
        Route::get('/zkteco/dtr', [ZktecoImportController::class, 'printDtr'])->name('hr.zkteco.dtr');
        Route::get('/zkteco/history', [ZktecoImportController::class, 'history'])->name('hr.zkteco.history');

        // Messages
        Route::get('/messages', [MessageController::class, 'index'])->name('hr.messages.index');
        Route::post('/messages', [MessageController::class, 'store'])->name('hr.messages.store');
        Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('hr.messages.destroy');

        // Board
        Route::get('/board', [BoardController::class, 'index'])->name('hr.board.index');
        Route::post('/board', [BoardController::class, 'store'])->name('hr.board.store');
        Route::delete('/board/{id}', [BoardController::class, 'destroy'])->name('hr.board.destroy');

        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('hr.profile.show');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('hr.profile.photo');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('hr.profile.password');
        Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])->name('hr.profile.signature');


    });

    /*
    |--------------------------------------------------------------------------
    | Department Head
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Department Head')->prefix('head')->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Head\DashboardController::class, 'index'])->name('head.dashboard');

        // Leave
        Route::get('/leave', [HeadLeaveController::class, 'index'])->name('head.leave.index');
        Route::get('/leave/{id}', [HeadLeaveController::class, 'show'])->name('head.leave.show');
        Route::put('/leave/{id}/approve', [HeadLeaveController::class, 'approve'])->name('head.leave.approve');
        Route::put('/leave/{id}/decline', [HeadLeaveController::class, 'decline'])->name('head.leave.decline');

        // Messages
        Route::get('/messages', [MessageController::class, 'index'])->name('head.messages.index');
        Route::post('/messages', [MessageController::class, 'store'])->name('head.messages.store');
        Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('head.messages.destroy');

        Route::get('/board', [BoardController::class, 'index'])->name('head.board.index');

        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('head.profile.show');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('head.profile.photo');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('head.profile.password');
        Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])->name('head.profile.signature');
    });

    /*
    |--------------------------------------------------------------------------
    | Administrative Officer (AO)
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Administrative Officer')->prefix('ao')->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\AO\DashboardController::class, 'index'])->name('ao.dashboard');

        // Leave
        Route::get('/leave', [AOLeaveController::class, 'index'])->name('ao.leave.index');
        Route::get('/leave/{id}', [AOLeaveController::class, 'show'])->name('ao.leave.show');
        Route::put('/leave/{id}/approve', [AOLeaveController::class, 'approve'])->name('ao.leave.approve');
        Route::put('/leave/{id}/decline', [AOLeaveController::class, 'decline'])->name('ao.leave.decline');

        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('ao.profile.show');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('ao.profile.photo');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('ao.profile.password');
        Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])->name('ao.profile.signature');
    });

    /*
    |--------------------------------------------------------------------------
    | ASDS
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:ASDS')->prefix('asds')->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\ASDS\DashboardController::class, 'index'])->name('asds.dashboard');

        // Leave
        Route::get('/leave', [ASDSLeaveController::class, 'index'])->name('asds.leave.index');
        Route::get('/leave/{id}', [ASDSLeaveController::class, 'show'])->name('asds.leave.show');
        Route::put('/leave/{id}/approve', [ASDSLeaveController::class, 'approve'])->name('asds.leave.approve');
        Route::put('/leave/{id}/decline', [ASDSLeaveController::class, 'decline'])->name('asds.leave.decline');

        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('asds.profile.show');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('asds.profile.photo');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('asds.profile.password');
        Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])->name('asds.profile.signature');
    });

    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth')->prefix('employee')->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('employee.dashboard');

        // Leave
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
        Route::get('/attendance/dtr', [App\Http\Controllers\Employee\AttendanceController::class, 'printDtr'])->name('employee.attendance.dtr');

        // Messages
        Route::get('/messages', [MessageController::class, 'index'])->name('employee.messages.index');
        Route::post('/messages', [MessageController::class, 'store'])->name('employee.messages.store');
        Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('employee.messages.destroy');

        Route::get('/board', [BoardController::class, 'index'])->name('employee.board.index');

        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('employee.profile.show');
        Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])->name('employee.profile.signature');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('employee.profile.photo');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('employee.profile.password');
        Route::get('/profile/edit', [App\Http\Controllers\Employee\EmployeeProfileController::class, 'edit'])->name('employee.profile.edit');
        Route::put('/profile/edit', [App\Http\Controllers\Employee\EmployeeProfileController::class, 'update'])->name('employee.profile.update');
    });
});
