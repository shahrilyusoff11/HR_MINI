<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee.user');
        
        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', today());
        }
        
        // Filter by employee
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $attendances = $query->latest()->paginate(20);
        $employees = Employee::with('user')->get();

        return view('attendances.index', compact('attendances', 'employees'));
    }

    public function create()
    {
        $employees = Employee::with('user')->get();
        return view('attendances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'required|in:present,absent,late,half_day',
            'notes' => 'nullable|string',
        ]);

        // Check if attendance already exists for this employee and date
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $request->date)
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Attendance already recorded for this employee on the selected date.');
        }

        Attendance::create($request->all());

        return redirect()->route('attendances.index')->with('success', 'Attendance recorded successfully.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('employee.user');
        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::with('user')->get();
        return view('attendances.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'required|in:present,absent,late,half_day',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($request->all());

        return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully.');
    }

    public function checkIn()
    {
        $employee = Auth::user()->employee;
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        if ($todayAttendance) {
            return redirect()->back()->with('error', 'You have already checked in today.');
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => today(),
            'check_in' => now(),
            'status' => 'present',
        ]);

        return redirect()->back()->with('success', 'Checked in successfully.');
    }

    public function checkOut()
    {
        $employee = Auth::user()->employee;
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        if (!$todayAttendance) {
            return redirect()->back()->with('error', 'You need to check in first.');
        }

        if ($todayAttendance->check_out) {
            return redirect()->back()->with('error', 'You have already checked out today.');
        }

        $todayAttendance->update([
            'check_out' => now(),
        ]);

        return redirect()->back()->with('success', 'Checked out successfully.');
    }
}