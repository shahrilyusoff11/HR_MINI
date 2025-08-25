<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Role-based Dashboard -->
            @if(auth()->user()->isAdmin() || auth()->user()->isHRManager())
                <!-- Admin/HR Manager Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Employees -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-blue-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Total Employees</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_employees'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Present Today -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-green-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Present Today</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['present_today'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Leaves -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-yellow-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Pending Leaves</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_leaves'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Payroll -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-red-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-100 text-red-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Pending Payroll</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_payrolls'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('employees.create') }}" class="block w-full text-center btn-primary">
                                Add New Employee
                            </a>
                            <a href="{{ route('attendances.create') }}" class="block w-full text-center btn-secondary">
                                Record Attendance
                            </a>
                            <a href="{{ route('payrolls.create') }}" class="block w-full text-center btn-success">
                                Process Payroll
                            </a>
                        </div>
                    </div>

                    <!-- Recent Leaves -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Leave Requests</h3>
                        @php
                            $recentLeaves = \App\Models\Leave::with(['employee.user', 'leaveType'])
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp
                        <div class="space-y-3">
                            @forelse($recentLeaves as $leave)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                    <div>
                                        <p class="text-sm font-medium">{{ $leave->employee->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $leave->leaveType->name }} - {{ $leave->days }} days</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $leave->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $leave->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No recent leave requests</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Upcoming Birthdays -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upcoming Birthdays</h3>
                        @php
                            $upcomingBirthdays = \App\Models\Employee::with('user')
                                ->whereMonth('date_of_birth', now()->month)
                                ->whereDay('date_of_birth', '>=', now()->day)
                                ->orderByRaw('DAY(date_of_birth)')
                                ->take(5)
                                ->get();
                        @endphp
                        <div class="space-y-3">
                            @forelse($upcomingBirthdays as $employee)
                                <div class="flex items-center p-3 bg-gray-50 rounded">
                                    <div class="flex-shrink-0">
                                        @if($employee->photo)
                                            <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $employee->photo) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-gray-600 text-sm">{{ substr($employee->user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium">{{ $employee->user->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($employee->date_of_birth)->format('M d') }} - 
                                            {{ $employee->date_of_birth->age + 1 }} years
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No upcoming birthdays this month</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            @else
                <!-- Employee Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Today's Attendance -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-blue-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Today's Status</h3>
                                    <p class="text-2xl font-bold text-gray-900">
                                        @if($stats['attendance_today'])
                                            {{ ucfirst($stats['attendance_today']->status) }}
                                        @else
                                            Not Recorded
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Leaves -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-yellow-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Pending Leaves</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_leaves'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Leaves -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-green-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Approved Leaves</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['approved_leaves'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Payrolls -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-purple-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Total Payrolls</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_payrolls'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employee Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            @if(!$stats['attendance_today'] || !$stats['attendance_today']->check_in)
                                <form action="{{ route('attendance.check-in') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-center btn-primary">
                                        Check In
                                    </button>
                                </form>
                            @endif

                            @if($stats['attendance_today'] && $stats['attendance_today']->check_in && !$stats['attendance_today']->check_out)
                                <form action="{{ route('attendance.check-out') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-center btn-secondary">
                                        Check Out
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('leaves.create') }}" class="block w-full text-center btn-success">
                                Apply for Leave
                            </a>

                            <a href="{{ route('profile.show') }}" class="block w-full text-center btn-secondary">
                                Update Profile
                            </a>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activities</h3>
                        @php
                            $recentActivities = collect();
                            
                            // Get recent leaves
                            $recentLeaves = auth()->user()->employee->leaves()
                                ->latest()
                                ->take(3)
                                ->get()
                                ->map(function($leave) {
                                    $leave->type = 'leave';
                                    $leave->description = "Leave application: {$leave->leaveType->name}";
                                    $leave->date = $leave->created_at;
                                    $leave->status = $leave->status;
                                    return $leave;
                                });
                            
                            // Get recent payrolls
                            $recentPayrolls = auth()->user()->employee->payrolls()
                                ->latest()
                                ->take(3)
                                ->get()
                                ->map(function($payroll) {
                                    $payroll->type = 'payroll';
                                    $payroll->description = "Payroll processed: $" . number_format($payroll->net_salary, 2);
                                    $payroll->date = $payroll->created_at;
                                    $payroll->status = $payroll->status;
                                    return $payroll;
                                });
                            
                            $recentActivities = $recentLeaves->merge($recentPayrolls)
                                ->sortByDesc('date')
                                ->take(5);
                        @endphp
                        <div class="space-y-3">
                            @forelse($recentActivities as $activity)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                    <div>
                                        <p class="text-sm font-medium">{{ $activity->description }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity->date->format('M d, Y H:i') }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $activity->status === 'approved' || $activity->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $activity->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $activity->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No recent activities</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>