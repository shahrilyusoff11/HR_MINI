<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Generate Reports</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Attendance Report Card -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900">Attendance Report</h4>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Generate monthly attendance reports for all employees.</p>
                            <form action="{{ route('reports.attendance') }}" method="POST">
                                @csrf
                                <div class="space-y-3">
                                    <div>
                                        <label for="attendance_month" class="form-label">Month</label>
                                        <select name="month" id="attendance_month" class="form-input" required>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label for="attendance_year" class="form-label">Year</label>
                                        <select name="year" id="attendance_year" class="form-input" required>
                                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button type="submit" class="btn-primary w-full">
                                        Generate Report
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Leave Report Card -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900">Leave Report</h4>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Generate monthly leave reports showing all leave applications.</p>
                            <form action="{{ route('reports.leave') }}" method="POST">
                                @csrf
                                <div class="space-y-3">
                                    <div>
                                        <label for="leave_month" class="form-label">Month</label>
                                        <select name="month" id="leave_month" class="form-input" required>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label for="leave_year" class="form-label">Year</label>
                                        <select name="year" id="leave_year" class="form-input" required>
                                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button type="submit" class="btn-primary w-full">
                                        Generate Report
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Payroll Report Card -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900">Payroll Report</h4>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Generate monthly payroll reports with salary details.</p>
                            <form action="{{ route('reports.payroll') }}" method="POST">
                                @csrf
                                <div class="space-y-3">
                                    <div>
                                        <label for="payroll_month" class="form-label">Month</label>
                                        <select name="month" id="payroll_month" class="form-input" required>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label for="payroll_year" class="form-label">Year</label>
                                        <select name="year" id="payroll_year" class="form-input" required>
                                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button type="submit" class="btn-primary w-full">
                                        Generate Report
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Employee Report Card -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                            <div class="flex items-center mb-4">
                                <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900">Employee Report</h4>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Generate comprehensive reports for individual employees.</p>
                            <form action="{{ route('reports.employee') }}" method="GET">
                                <div class="space-y-3">
                                    <div>
                                        <label for="employee_id" class="form-label">Employee</label>
                                        <select name="employee_id" id="employee_id" class="form-input" required>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->user->name }} - {{ $employee->department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn-primary w-full">
                                        Generate Report
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>