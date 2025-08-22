<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Payroll::with('employee.user');

        if ($user->isEmployee()) {
            $query->where('employee_id', $user->employee->id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by pay period
        if ($request->has('month') && $request->has('year')) {
            $query->whereYear('pay_period_start', $request->year)
                ->whereMonth('pay_period_start', $request->month);
        }

        $payrolls = $query->latest()->paginate(15);
        $employees = Employee::with('user')->get();

        return view('payrolls.index', compact('payrolls', 'employees'));
    }

    public function create()
    {
        $employees = Employee::with('user')->get();
        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after:pay_period_start',
            'basic_salary' => 'required|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate net salary
        $netSalary = $request->basic_salary + $request->overtime + $request->bonus - $request->deductions;

        Payroll::create([
            'employee_id' => $request->employee_id,
            'pay_period_start' => $request->pay_period_start,
            'pay_period_end' => $request->pay_period_end,
            'basic_salary' => $request->basic_salary,
            'overtime' => $request->overtime ?? 0,
            'bonus' => $request->bonus ?? 0,
            'deductions' => $request->deductions ?? 0,
            'net_salary' => $netSalary,
            'notes' => $request->notes,
        ]);

        return redirect()->route('payrolls.index')->with('success', 'Payroll created successfully.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee.user');
        return view('payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        $employees = Employee::with('user')->get();
        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after:pay_period_start',
            'basic_salary' => 'required|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,processed,paid',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate net salary
        $netSalary = $request->basic_salary + $request->overtime + $request->bonus - $request->deductions;

        $payroll->update([
            'employee_id' => $request->employee_id,
            'pay_period_start' => $request->pay_period_start,
            'pay_period_end' => $request->pay_period_end,
            'basic_salary' => $request->basic_salary,
            'overtime' => $request->overtime ?? 0,
            'bonus' => $request->bonus ?? 0,
            'deductions' => $request->deductions ?? 0,
            'net_salary' => $netSalary,
            'status' => $request->status,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('payrolls.index')->with('success', 'Payroll updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->route('payrolls.index')->with('success', 'Payroll deleted successfully.');
    }

    public function generatePayslip(Payroll $payroll)
    {
        $payroll->load('employee.user');
        
        $pdf = PDF::loadView('payrolls.payslip', compact('payroll'));
        return $pdf->download('payslip-' . $payroll->employee->user->name . '-' . $payroll->pay_period_start->format('F-Y') . '.pdf');
    }

    public function markAsPaid(Payroll $payroll)
    {
        $payroll->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Payroll marked as paid successfully.');
    }
}