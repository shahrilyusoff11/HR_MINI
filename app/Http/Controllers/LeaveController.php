<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Leave::with(['employee.user', 'leaveType', 'approvedBy']);

        if ($user->isEmployee()) {
            $query->where('employee_id', $user->employee->id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->latest()->paginate(15);
        $leaveTypes = LeaveType::all();

        return view('leaves.index', compact('leaves', 'leaveTypes'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::all();
        $employees = Employee::with('user')->get();
        return view('leaves.create', compact('leaveTypes', 'employees'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->isEmployee() ? $user->employee->id : $request->employee_id;

        $request->validate([
            'employee_id' => $user->isEmployee() ? 'nullable' : 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        // Calculate number of days
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1; // Inclusive of both dates

        // Check available leave balance
        $leaveType = LeaveType::find($request->leave_type_id);
        $usedDays = Leave::where('employee_id', $employeeId)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('status', 'approved')
            ->sum('days');

        $availableDays = $leaveType->days_per_year - $usedDays;

        if ($days > $availableDays) {
            return redirect()->back()->with('error', "Insufficient leave balance. Available: {$availableDays} days, Requested: {$days} days.");
        }

        Leave::create([
            'employee_id' => $employeeId,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days' => $days,
            'reason' => $request->reason,
            'status' => $user->isEmployee() ? 'pending' : 'approved',
        ]);

        $message = $user->isEmployee() ? 'Leave request submitted successfully.' : 'Leave created successfully.';
        return redirect()->route('leaves.index')->with('success', $message);
    }

    public function show(Leave $leave)
    {
        $leave->load(['employee.user', 'leaveType', 'approvedBy']);
        return view('leaves.show', compact('leave'));
    }

    public function edit(Leave $leave)
    {
        $leaveTypes = LeaveType::all();
        $employees = Employee::with('user')->get();
        return view('leaves.edit', compact('leave', 'leaveTypes', 'employees'));
    }

    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        // Calculate number of days
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1;

        $leave->update([
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days' => $days,
            'reason' => $request->reason,
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave updated successfully.');
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave deleted successfully.');
    }

    public function approve(Leave $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave approved successfully.');
    }

    public function reject(Request $request, Leave $leave)
    {
        $request->validate([
            'comments' => 'required|string|max:1000',
        ]);

        $leave->update([
            'status' => 'rejected',
            'comments' => $request->comments,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave rejected successfully.');
    }

    public function checkBalance(LeaveType $leaveType)
    {
        $user = Auth::user();
        $employeeId = $user->isEmployee() ? $user->employee->id : request('employee_id');
        
        if (!$employeeId) {
            return response()->json([
                'available' => 0,
                'used' => 0,
                'total' => $leaveType->days_per_year
            ]);
        }

        $usedDays = Leave::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveType->id)
            ->where('status', 'approved')
            ->sum('days');

        $availableDays = $leaveType->days_per_year - $usedDays;

        return response()->json([
            'available' => $availableDays,
            'used' => $usedDays,
            'total' => $leaveType->days_per_year
        ]);
    }
}