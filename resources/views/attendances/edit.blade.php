<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('attendances.update', $attendance) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="employee_id" :value="__('Employee')" />
                                <select id="employee_id" name="employee_id" class="form-input" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id', $attendance->employee_id) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->user->name }} - {{ $employee->department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('employee_id')" />
                            </div>

                            <div>
                                <x-input-label for="date" :value="__('Date')" />
                                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" 
                                             :value="old('date', $attendance->date->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('date')" />
                            </div>

                            <div>
                                <x-input-label for="check_in" :value="__('Check In Time')" />
                                <x-text-input id="check_in" name="check_in" type="time" class="mt-1 block w-full" 
                                             :value="old('check_in', $attendance->check_in ? $attendance->check_in->format('H:i') : '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('check_in')" />
                            </div>

                            <div>
                                <x-input-label for="check_out" :value="__('Check Out Time')" />
                                <x-text-input id="check_out" name="check_out" type="time" class="mt-1 block w-full" 
                                             :value="old('check_out', $attendance->check_out ? $attendance->check_out->format('H:i') : '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('check_out')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="form-input" required>
                                    <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="half_day" {{ old('status', $attendance->status) == 'half_day' ? 'selected' : '' }}>Half Day</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Notes')" />
                                <textarea id="notes" name="notes" class="form-input" rows="3">{{ old('notes', $attendance->notes) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Attendance') }}</x-primary-button>
                            <a href="{{ route('attendances.index') }}" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>