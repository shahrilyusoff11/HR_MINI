<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Leave Requests</h3>
                        @can('access', ['employee'])
                        <a href="{{ route('leaves.create') }}" class="btn-primary">
                            Apply for Leave
                        </a>
                        @endcan
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('leaves.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-input">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                            <div>
                                <label for="leave_type_id" class="form-label">Leave Type</label>
                                <select name="leave_type_id" id="leave_type_id" class="form-input">
                                    <option value="">All Types</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="btn-primary w-full">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Leave Requests Table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    @can('access', ['admin', 'hr_manager'])
                                    <th>Employee</th>
                                    @endcan
                                    <th>Leave Type</th>
                                    <th>Period</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Applied On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaves as $leave)
                                    <tr>
                                        @can('access', ['admin', 'hr_manager'])
                                        <td>
                                            <div class="flex items-center">
                                                @if($leave->employee->photo)
                                                    <img src="{{ asset('storage/' . $leave->employee->photo) }}" 
                                                         alt="{{ $leave->employee->user->name }}" 
                                                         class="w-8 h-8 rounded-full object-cover mr-2">
                                                @endif
                                                {{ $leave->employee->user->name }}
                                            </div>
                                        </td>
                                        @endcan
                                        <td>{{ $leave->leaveType->name }}</td>
                                        <td>
                                            {{ $leave->start_date->format('M d') }} - 
                                            {{ $leave->end_date->format('M d, Y') }}
                                        </td>
                                        <td>{{ $leave->days }}</td>
                                        <td class="max-w-xs truncate">{{ $leave->reason }}</td>
                                        <td>
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $leave->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $leave->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $leave->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('leaves.show', $leave) }}" class="text-blue-600 hover:text-blue-900">
                                                    View
                                                </a>
                                                @can('access', ['admin', 'hr_manager'])
                                                @if($leave->status === 'pending')
                                                    <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    <button type="button" 
                                                            onclick="openRejectModal({{ $leave->id }})" 
                                                            class="text-red-600 hover:text-red-900">
                                                        Reject
                                                    </button>
                                                @endif
                                                @endcan
                                                @can('access', ['employee'])
                                                @if($leave->status === 'pending')
                                                    <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" 
                                                                onclick="return confirm('Are you sure you want to delete this leave request?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->isEmployee() ? 7 : 8 }}" class="text-center py-4">
                                            No leave requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $leaves->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    @can('access', ['admin', 'hr_manager'])
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Leave Request</h3>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="comments" class="form-label">Reason for Rejection</label>
                        <textarea id="comments" name="comments" class="form-input" rows="3" required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()" class="btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn-danger">
                            Reject Leave
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(leaveId) {
            document.getElementById('rejectForm').action = `/leaves/${leaveId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('comments').value = '';
        }

        // Close modal if clicked outside
        window.onclick = function(event) {
            const modal = document.getElementById('rejectModal');
            if (event.target === modal) {
                closeRejectModal();
            }
        }
    </script>
    @endcan
</x-app-layout>