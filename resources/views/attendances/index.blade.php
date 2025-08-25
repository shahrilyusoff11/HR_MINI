<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Attendance Records</h3>
                        @can('access', ['admin', 'hr_manager'])
                        <a href="{{ route('attendances.create') }}" class="btn-primary">
                            Record Attendance
                        </a>
                        @endcan
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('attendances.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" id="date" class="form-input" 
                                       value="{{ request('date', today()->format('Y-m-d')) }}">
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
                            <div class="flex items-end">
                                <a href="{{ route('attendances.index') }}" class="btn-secondary w-full text-center">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Check-in/Check-out Buttons for Employees -->
                    @can('access', ['employee'])
                    <div class="mb-6">
                        @php
                            $todayAttendance = auth()->user()->employee->attendances()
                                ->whereDate('date', today())
                                ->first();
                        @endphp
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if(!$todayAttendance || !$todayAttendance->check_in)
                                <form action="{{ route('attendance.check-in') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-primary w-full">
                                        Check In Now
                                    </button>
                                </form>
                            @else
                                <button class="btn-secondary w-full" disabled>
                                    Checked In at {{ $todayAttendance->check_in->format('H:i') }}
                                </button>
                            @endif

                            @if($todayAttendance && $todayAttendance->check_in && !$todayAttendance->check_out)
                                <form action="{{ route('attendance.check-out') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-primary w-full">
                                        Check Out Now
                                    </button>
                                </form>
                            @elseif($todayAttendance && $todayAttendance->check_out)
                                <button class="btn-secondary w-full" disabled>
                                    Checked Out at {{ $todayAttendance->check_out->format('H:i') }}
                                </button>
                            @else
                                <button class="btn-secondary w-full" disabled>
                                    Check Out
                                </button>
                            @endif
                        </div>
                    </div>
                    @endcan

                    <!-- Attendance Table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    @can('access', ['admin', 'hr_manager'])
                                    <th>Employee</th>
                                    @endcan
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th>Hours Worked</th>
                                    <th>Notes</th>
                                    @can('access', ['admin', 'hr_manager'])
                                    <th>Actions</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                    <tr>
                                        @can('access', ['admin', 'hr_manager'])
                                        <td>
                                            <div class="flex items-center">
                                                @if($attendance->employee->photo)
                                                    <img src="{{ asset('storage/' . $attendance->employee->photo) }}" 
                                                         alt="{{ $attendance->employee->user->name }}" 
                                                         class="w-8 h-8 rounded-full object-cover mr-2">
                                                @endif
                                                {{ $attendance->employee->user->name }}
                                            </div>
                                        </td>
                                        @endcan
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
                                        <td class="max-w-xs truncate">{{ $attendance->notes ?? '--' }}</td>
                                        @can('access', ['admin', 'hr_manager'])
                                        <td>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('attendances.edit', $attendance) }}" class="text-green-600 hover:text-green-900">
                                                    Edit
                                                </a>
                                                <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('Are you sure you want to delete this attendance record?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->isEmployee() ? 7 : 8 }}" class="text-center py-4">
                                            No attendance records found for the selected date.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>