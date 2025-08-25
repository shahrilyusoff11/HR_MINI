<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/update-employee', [ProfileController::class, 'updateEmployeeProfile'])->name('update-employee');
    });

    // Admin and HR Manager only routes
    Route::middleware(['role:admin,hr_manager'])->group(function () {
        // Employee Routes
        Route::resource('employees', EmployeeController::class)->except(['show']);
        
        // Department Routes
        Route::resource('departments', DepartmentController::class);
        
        // Attendance Routes (full access)
        Route::resource('attendances', AttendanceController::class);
        
        // Leave Routes (approval)
        Route::prefix('leaves')->name('leaves.')->group(function () {
            Route::post('/{leave}/approve', [LeaveController::class, 'approve'])->name('approve');
            Route::post('/{leave}/reject', [LeaveController::class, 'reject'])->name('reject');
        });
        
        // Payroll Routes
        Route::resource('payrolls', PayrollController::class);
        Route::post('/payrolls/{payroll}/mark-paid', [PayrollController::class, 'markAsPaid'])->name('payrolls.mark-paid');
        Route::get('/payrolls/{payroll}/payslip', [PayrollController::class, 'generatePayslip'])->name('payrolls.payslip');
        
        // Performance Review Routes
        Route::resource('performance-reviews', PerformanceReviewController::class);
        
        // Report Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::post('/attendance', [ReportController::class, 'attendanceReport'])->name('attendance');
            Route::post('/leave', [ReportController::class, 'leaveReport'])->name('leave');
            Route::post('/payroll', [ReportController::class, 'payrollReport'])->name('payroll');
            Route::get('/employee/{employee}', [ReportController::class, 'employeeReport'])->name('employee');
        });
    });

    // Employee accessible routes
    Route::middleware(['role:employee'])->group(function () {
        // Attendance check-in/check-out
        Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
        Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
        
        // Leave application
        Route::resource('leaves', LeaveController::class)->only(['index', 'create', 'store', 'show']);
        
        // Payroll view
        Route::resource('payrolls', PayrollController::class)->only(['index', 'show']);
        
        // Performance reviews view
        Route::resource('performance-reviews', PerformanceReviewController::class)->only(['index', 'show']);
    });

    // Shared routes (both HR/Admin and Employees)
    Route::middleware(['role:admin,hr_manager,employee'])->group(function () {
        // Employee show route
        Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
        
        // Attendance index with filters
        Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        
        // Leave index with filters
        Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');

        Route::get('/leaves/balance/{leaveType}', [LeaveController::class, 'checkBalance'])->name('leaves.balance');

        Route::get('/employees/{employee}/salary', [EmployeeController::class, 'getSalary'])->name('employees.salary');
    });
});

require __DIR__.'/auth.php';