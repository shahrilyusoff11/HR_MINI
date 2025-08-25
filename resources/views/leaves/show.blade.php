<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave Request Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Leave Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Leave Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Employee</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $leave->employee->user->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Leave Type</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $leave->leaveType->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Period</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $leave->start_date->format('M d, Y') }} to {{ $leave->end_date->format('M d, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Number of Days</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $leave->days }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Status</label>
                                    <p class="mt-1">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $leave->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $leave->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </p>
                                </div>
                                @if($leave->approved_by)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Approved By</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $leave->approvedBy->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Approved At</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $leave->approved_at->format('M d, Y H:i') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Reason and Comments -->
                        <div>
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Reason for Leave</h4>
                                <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $leave->reason }}</p>
                            </div>

                            @if($leave->comments)
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Manager Comments</h4>
                                <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $leave->comments }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 border-t pt-6">
                        <div class="flex space-x-4">
                            <a href="{{ route('leaves.index') }}" class="btn-secondary">
                                Back to List
                            </a>
                            
                            @can('access', ['admin', 'hr_manager'])
                            @if($leave->status === 'pending')
                                <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn-success">
                                        Approve Leave
                                    </button>
                                </form>
                                <button type="button" 
                                        onclick="openRejectModal()" 
                                        class="btn-danger">
                                    Reject Leave
                                </button>
                            @endif
                            @endcan

                            @can('access', ['employee'])
                            @if($leave->status === 'pending')
                                <a href="{{ route('leaves.edit', $leave) }}" class="btn-primary">
                                    Edit Request
                                </a>
                                <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this leave request?')">
                                        Delete Request
                                    </button>
                                </form>
                            @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    @can('access', ['admin', 'hr_manager'])
    @if($leave->status === 'pending')
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Leave Request</h3>
                <form action="{{ route('leaves.reject', $leave) }}" method="POST">
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
        function openRejectModal() {
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
    @endif
    @endcan
</x-app-layout>