<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Employee Photo and Basic Info -->
                        <div class="md:col-span-1">
                            <div class="text-center">
                                @if($employee->photo)
                                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->user->name }}" 
                                         class="w-32 h-32 rounded-full object-cover mx-auto">
                                @else
                                    <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center mx-auto">
                                        <span class="text-gray-600 text-2xl">{{ substr($employee->user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <h3 class="text-xl font-bold mt-4">{{ $employee->user->name }}</h3>
                                <p class="text-gray-600">{{ $employee->position }}</p>
                                <p class="text-sm text-gray-500">{{ $employee->department->name }}</p>
                            </div>

                            <div class="mt-6 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Employee ID:</span>
                                    <span class="font-medium">#{{ $employee->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Active</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Years of Service:</span>
                                    <span class="font-medium">{{ $employee->years_of_service }} years</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Age:</span>
                                    <span class="font-medium">{{ $employee->age }} years</span>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Details -->
                        <div class="md:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Contact Information -->
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Email</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $employee->user->email }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Phone</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $employee->phone }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Address</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $employee->address }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Employment Details -->
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Employment Details</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Department</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $employee->department->name }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Position</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $employee->position }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Salary</label>
                                            <p class="mt-1 text-sm text-gray-900">${{ number_format($employee->salary, 2) }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Hire Date</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $employee->hire_date->format('M d, Y') }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Date of Birth</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $employee->date_of_birth->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $employee->leaves->count() }}</div>
                                    <div class="text-sm text-gray-600">Total Leaves</div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $employee->payrolls->count() }}</div>
                                    <div class="text-sm text-gray-600">Payroll Records</div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-purple-600">{{ $employee->performanceReviews->count() }}</div>
                                    <div class="text-sm text-gray-600">Performance Reviews</div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 flex space-x-4">
                                <a href="{{ route('employees.edit', $employee) }}" class="btn-primary">
                                    Edit Employee
                                </a>
                                <a href="{{ route('employees.index') }}" class="btn-secondary">
                                    Back to List
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs for Additional Information -->
                    <div class="mt-8">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8">
                                <button type="button" class="tab-link active" data-tab="attendance">
                                    Attendance
                                </button>
                                <button type="button" class="tab-link" data-tab="leaves">
                                    Leaves
                                </button>
                                <button type="button" class="tab-link" data-tab="payroll">
                                    Payroll
                                </button>
                                <button type="button" class="tab-link" data-tab="performance">
                                    Performance
                                </button>
                            </nav>
                        </div>

                        <!-- Tab Content -->
                        <div class="mt-4">
                            <!-- Attendance Tab -->
                            <div id="attendance-tab" class="tab-content active">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Attendance Records</h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                                <th>Status</th>
                                                <th>Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($employee->attendances->take(5) as $attendance)
                                                <tr>
                                                    <td>{{ $attendance->date->format('M d, Y') }}</td>
                                                    <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i') : '--' }}</td>
                                                    <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i') : '--' }}</td>
                                                    <td>
                                                        <span class="px-2 py-1 text-xs rounded-full 
                                                            {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : '' }}
                                                            {{ $attendance->status === 'absent' ? 'bg-red-100 text-red-800' : '' }}
                                                            {{ $attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                            {{ $attendance->status === 'half_day' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                            {{ ucfirst($attendance->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $attendance->hours_worked }} hours</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">No attendance records found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Leaves Tab -->
                            <div id="leaves-tab" class="tab-content hidden">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Leave History</h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Period</th>
                                                <th>Days</th>
                                                <th>Status</th>
                                                <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($employee->leaves->take(5) as $leave)
                                                <tr>
                                                    <td>{{ $leave->leaveType->name }}</td>
                                                    <td>{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</td>
                                                    <td>{{ $leave->days }}</td>
                                                    <td>
                                                        <span class="px-2 py-1 text-xs rounded-full 
                                                            {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                            {{ $leave->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                            {{ $leave->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                                            {{ ucfirst($leave->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="truncate max-w-xs">{{ $leave->reason }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">No leave records found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Payroll Tab -->
                            <div id="payroll-tab" class="tab-content hidden">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Payroll History</h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Period</th>
                                                <th>Basic Salary</th>
                                                <th>Allowances</th>
                                                <th>Deductions</th>
                                                <th>Net Salary</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($employee->payrolls->take(5) as $payroll)
                                                <tr>
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
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">No payroll records found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Performance Tab -->
                            <div id="performance-tab" class="tab-content hidden">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Performance Reviews</h4>
                                <div class="space-y-4">
                                    @forelse($employee->performanceReviews->take(3) as $review)
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h5 class="font-medium">Review by {{ $review->reviewer->name }}</h5>
                                                    <p class="text-sm text-gray-600">{{ $review->review_date->format('M d, Y') }}</p>
                                                </div>
                                                <span class="px-2 py-1 text-xs rounded-full {{ $review->rating_class }}">
                                                    {{ $review->rating_text }} ({{ $review->rating }}/5)
                                                </span>
                                            </div>
                                            <div class="mt-3">
                                                <p class="text-sm text-gray-800"><strong>Comments:</strong> {{ $review->comments }}</p>
                                                <p class="text-sm text-gray-800 mt-2"><strong>Goals:</strong> {{ $review->goals }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-600">No performance reviews found.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            tabLinks.forEach(link => {
                link.addEventListener('click', function() {
                    const tab = this.getAttribute('data-tab');
                    
                    // Update active tab
                    tabLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show active content
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                        content.classList.add('hidden');
                    });
                    
                    const activeContent = document.getElementById(`${tab}-tab`);
                    activeContent.classList.remove('hidden');
                    activeContent.classList.add('active');
                });
            });
        });
    </script>

    <style>
        .tab-link {
            @apply py-4 px-1 border-b-2 font-medium text-sm;
        }
        .tab-link.active {
            @apply border-blue-500 text-blue-600;
        }
        .tab-link:not(.active) {
            @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300;
        }
        .tab-content {
            @apply hidden;
        }
        .tab-content.active {
            @apply block;
        }
    </style>
</x-app-layout>