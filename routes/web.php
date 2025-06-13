<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeAttendenceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InternalsheetController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\WorkmanController;
use App\Models\Designation;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfileForm'])->name('profile');
    Route::post('/profile/update-email', [AuthController::class, 'updateEmail'])->name('profile.update-email');
    Route::post('/profile/update-password', [AuthController::class, 'updatePassword'])->name('profile.update-password');

    Route::get('/sites', [PageController::class, 'sitePage'])->name('locations');

    Route::get('/locations', [LocationsController::class, 'index'])->name('locations.index');
    Route::post('/locations', [LocationsController::class, 'store'])->name('locations.store');
    Route::put('/locations/{location}', [LocationsController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationsController::class, 'destroy'])->name('locations.destroy');

    Route::get('/workmen', [WorkmanController::class, 'index'])->name('workmen');
    Route::get('/new-workmen', [WorkmanController::class, 'create'])->name('new-workmen');
    Route::post('/workmen', [WorkmanController::class, 'store'])->name('new-workmen.store');
    Route::get('/workmen/{workman}/edit', [WorkmanController::class, 'edit'])->name('workmen.edit');
    Route::put('/workmen/{workman}', [WorkmanController::class, 'update'])->name('workmen.update');
    Route::delete('/workmen/{workman}', [WorkmanController::class, 'destroy'])->name('workmen.destroy');
    Route::get('/workmen/{workman}/download-pdf', [WorkmanController::class, 'downloadPdf'])->name('workmen.download-pdf');
    Route::put('/designations/{id}', [DesignationController::class, 'update'])->name('designations.update');

    Route::get('/designation', [DesignationController::class, 'index'])->name('designation.index');
    Route::post('/designation', [DesignationController::class, 'store'])->name('designations.store');
    Route::delete('/designation/{id}', [DesignationController::class, 'destroy'])->name('designations.destroy');
    Route::get('/attendence', [AttendanceController::class, 'index'])->name('attendence');
    Route::post('/attendence', [AttendanceController::class, 'store'])->name('attendence.store');


    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/download-pdf', [ReportsController::class, 'downloadPdf'])->name('reports.download-pdf');
    Route::get('/reports/download-csv', [ReportsController::class, 'downloadCsv'])->name('reports.download-csv');

    Route::get('/hr-report',[InternalsheetController::class,'HRIndex'])->name('hr.report');
    Route::post('/hr-report', [InternalsheetController::class, 'getHRReport'])->name('hr-report-fetch');
    Route::get('/employee-report',[InternalsheetController::class,'employeeIndex'])->name('employee.internal.report');
    Route::post('/employee-report',[InternalsheetController::class,'getEmployeeReport'])->name('employee.internal.fetch');


});

Route::middleware(['auth'])->group(function () {
    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/new-employee', [EmployeeController::class, 'create'])->name('new-employee');
    Route::post('/employee', [EmployeeController::class, 'store'])->name('new-employee.store');
    Route::get('/employee/{employee}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('/employee/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/employee/{employee}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
    Route::get('/employee/{employee}/download-pdf', [EmployeeController::class, 'downloadPdf'])->name('employee.download-pdf');
    Route::get('/EmployeeAttendence', [EmployeeAttendenceController::class, 'index'])->name('EmployeeAttendence');
    Route::post('/EmployeeAttendence', [EmployeeAttendenceController::class, 'store'])->name('EmployeeAttendence.store');
    Route::post('/internal/slip',[PayslipController::class,'getInternalSlip'])->name('internal.payslip');
    Route::post('/internal/slip/employee',[PayslipController::class,'getEmployeeSlip'])->name('internal.payslip.getEmployeeSlip');
});
