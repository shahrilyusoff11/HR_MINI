<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function attendanceReport(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $attendances = Attendance::with('employee.user')
            ->whereYear('date', $request->year)
            ->whereMonth('date', $request->month)
            ->get()
            ->groupBy('employee_id');

        $pdf = PDF::loadView('reports.attendance', compact('attendances', 'request'));
        return $pdf->download('attendance-report-' . $request->month . '-' . $request->year . '.pdf');
    }

    public function leaveReport(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $leaves = Leave::with(['employee.user', 'leaveType'])
            ->whereYear('start_date', $request->year)
            ->whereMonth('start_date', $request->month)
            ->get();

        $pdf = PDF::loadView('reports.leave', compact('leaves', 'request'));
        return $pdf->download('leave-report-' . $request->month . '-' . $request->year . '.pdf');
    }

    public function payrollReport(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $payrolls = Payroll::with('employee.user')
            ->whereYear('pay_period_start', $request->year)
            ->whereMonth('pay_period_start', $request->month)
            ->get();

        $pdf = PDF::loadView('reports.payroll', compact('payrolls', 'request'));
        return $pdf->download('payroll-report-' . $request->month . '-' . $request->year . '.pdf');
    }

    public function employeeReport(Employee $employee)
    {
        $employee->load(['user', 'department', 'leaves.leaveType', 'payrolls', 'performanceReviews.reviewer']);
        
        $pdf = PDF::loadView('reports.employee', compact('employee'));
        return $pdf->download('employee-report-' . $employee->user->name . '.pdf');
    }
}