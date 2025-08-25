<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Apply for Leave') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('leaves.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @can('access', ['admin', 'hr_manager'])
                            <div>
                                <x-input-label for="employee_id" :value="__('Employee')" />
                                <select id="employee_id" name="employee_id" class="form-input" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->user->name }} - {{ $employee->department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('employee_id')" />
                            </div>
                            @endcan

                            <div>
                                <x-input-label for="leave_type_id" :value="__('Leave Type')" />
                                <select id="leave_type_id" name="leave_type_id" class="form-input" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }} ({{ $type->days_per_year }} days/year)
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('leave_type_id')" />
                            </div>

                            <div>
                                <x-input-label for="start_date" :value="__('Start Date')" />
                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" 
                                             :value="old('start_date')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                            </div>

                            <div>
                                <x-input-label for="end_date" :value="__('End Date')" />
                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" 
                                             :value="old('end_date')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="reason" :value="__('Reason')" />
                                <textarea id="reason" name="reason" class="form-input" rows="4" required>{{ old('reason') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('reason')" />
                            </div>
                        </div>

                        <!-- Leave Balance Information -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Leave Balance Information</h4>
                            <div id="leaveBalanceInfo" class="text-sm text-blue-700">
                                Select a leave type to see your available balance.
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Submit Leave Request') }}</x-primary-button>
                            <a href="{{ route('leaves.index') }}" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const leaveTypeSelect = document.getElementById('leave_type_id');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const balanceInfo = document.getElementById('leaveBalanceInfo');

            // Set minimum dates to today
            const today = new Date().toISOString().split('T')[0];
            startDateInput.min = today;
            endDateInput.min = today;

            // Update end date min when start date changes
            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
                calculateDays();
            });

            endDateInput.addEventListener('change', calculateDays);

            function calculateDays() {
                if (startDateInput.value && endDateInput.value) {
                    const start = new Date(startDateInput.value);
                    const end = new Date(endDateInput.value);
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    
                    if (diffDays > 0) {
                        balanceInfo.textContent += ` | ${diffDays} days requested`;
                    }
                }
            }

            // Fetch leave balance when leave type is selected
            leaveTypeSelect.addEventListener('change', function() {
                const leaveTypeId = this.value;
                if (!leaveTypeId) {
                    balanceInfo.textContent = 'Select a leave type to see your available balance.';
                    return;
                }

                fetch(`/leaves/balance/${leaveTypeId}`)
                    .then(response => response.json())
                    .then(data => {
                        balanceInfo.textContent = 
                            `Available: ${data.available} days | Used: ${data.used} days | Total: ${data.total} days per year`;
                    })
                    .catch(error => {
                        balanceInfo.textContent = 'Error fetching leave balance.';
                    });
            });
        });
    </script>
</x-app-layout>