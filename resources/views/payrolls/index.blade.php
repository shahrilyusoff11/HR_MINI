<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payroll Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Payroll Records</h3>
                        @can('access', ['admin', 'hr_manager'])
                        <a href="{{ route('payrolls.create') }}" class="btn-primary">
                            Process Payroll
                        </a>
                        @endcan
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('payrolls.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="month" class="form-label">Month</label>
                                <select name="month" id="month" class="form-input">
                                    <option value="">All Months</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label for="year" class="form-label">Year</label>
                                <select name="year" id="year" class="form-input">
                                    <option value="">All Years</option>
                                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            @can('access', ['admin', 'hr_manager'])
                            <div>
                                <label for="employee_id" class="form-label">Employee</label>
                                <select name="employee_id" id="employee_id" class="form-input">
                                    <option value="">All Employees</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endcan
                            <div class="flex items-end">
                                <button type="submit" class="btn-primary w-full">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Payroll Table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    @can('access', ['admin', 'hr_manager'])
                                    <th>Employee</th>
                                    @endcan
                                    <th>Pay Period</th>
                                    <th>Basic Salary</th>
                                    <th>Allowances</th>
                                    <th>Deductions</th>
                                    <th>Net Salary</th>
                                    <th>Status</th>
                                    <th>Payment Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrolls as $payroll)
                                    <tr>
                                        @can('access', ['admin', 'hr_manager'])
                                        <td>
                                            <div class="flex items-center">
                                                @if($payroll->employee->photo)
                                                    <img src="{{ asset('storage/' . $payroll->employee->photo) }}" 
                                                         alt="{{ $payroll->employee->user->name }}" 
                                                         class="w-8 h-8 rounded-full object-cover mr-2">
                                                @endif
                                                {{ $payroll->employee->user->name }}
                                            </div>
                                        </td>
                                        @endcan
                                        <td>{{ $payroll->pay_period }}</td>
                                        <td>${{ number_format($payroll->basic_salary, 2) }}</td>
                                        <td>${{ number_format($payroll->overtime + $payroll->bonus, 2) }}</td>
                                        <td>${{ number_format($payroll->deductions, 2) }}</td>
                                        <td>${{ number_format($payroll->net_salary, 2) }}</td>
                                        <td>
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                {{ $payroll->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $payroll->status === 'processed' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $payroll->status === 'pending' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($payroll->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $payroll->payment_date ? $payroll->payment_date->format('M d, Y') : '--' }}</td>
                                        <td>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('payrolls.show', $payroll) }}" class="text-blue-600 hover:text-blue-900">
                                                    View
                                                </a>
                                                @can('access', ['admin', 'hr_manager'])
                                                <a href="{{ route('payrolls.payslip', $payroll) }}" class="text-purple-600 hover:text-purple-900">
                                                    Payslip
                                                </a>
                                                @if($payroll->status === 'processed')
                                                    <form action="{{ route('payrolls.mark-paid', $payroll) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                                            Mark Paid
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('payrolls.edit', $payroll) }}" class="text-green-600 hover:text-green-900">
                                                    Edit
                                                </a>
                                                <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('Are you sure you want to delete this payroll record?')">
                                                        Delete
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->isEmployee() ? 8 : 9 }}" class="text-center py-4">
                                            No payroll records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $payrolls->links() }}
                    </div>

                    <!-- Summary Statistics -->
                    @can('access', ['admin', 'hr_manager'])
                    @if($payrolls->count() > 0)
                    <div class="mt-8 border-t pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Payroll Summary</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-blue-600">${{ number_format($payrolls->sum('basic_salary'), 2) }}</div>
                                <div class="text-sm text-blue-600">Total Basic Salary</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-green-600">${{ number_format($payrolls->sum('overtime') + $payrolls->sum('bonus'), 2) }}</div>
                                <div class="text-sm text-green-600">Total Allowances</div>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-red-600">${{ number_format($payrolls->sum('deductions'), 2) }}</div>
                                <div class="text-sm text-red-600">Total Deductions</div>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-purple-600">${{ number_format($payrolls->sum('net_salary'), 2) }}</div>
                                <div class="text-sm text-purple-600">Total Net Salary</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endcan
                </div>
            </div>
</x-app-layout>