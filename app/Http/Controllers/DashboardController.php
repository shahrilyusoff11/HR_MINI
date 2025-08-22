<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = [];

        if ($user->isAdmin() || $user->isHRManager()) {
            $stats = [
                'total_employees' => Employee::count(),
                'present_today' => Attendance::whereDate('date', today())->where('status', 'present')->count(),
                'pending_leaves' => Leave::pending()->count(),
                'pending_payrolls' => Payroll::pending()->count(),
            ];
        } else {
            $employee = $user->employee;
            if ($employee) {
                $stats = [
                    'attendance_today' => Attendance::where('employee_id', $employee->id)
                        ->whereDate('date', today())
                        ->first(),
                    'pending_leaves' => $employee->leaves()->pending()->count(),
                    'approved_leaves' => $employee->leaves()->approved()->count(),
                    'total_payrolls' => $employee->payrolls()->count(),
                ];
            }
        }

        return view('dashboard', compact('stats'));
    }
}