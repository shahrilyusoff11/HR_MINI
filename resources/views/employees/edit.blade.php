<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                            </div>

                            <div>
                                <x-input-label for="name" :value="__('Full Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                             :value="old('name', $employee->user->name)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email Address')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                             :value="old('email', $employee->user->email)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <!-- Employee Information -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 mt-6">Employee Information</h3>
                            </div>

                            <div>
                                <x-input-label for="department_id" :value="__('Department')" />
                                <select id="department_id" name="department_id" class="form-input" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                            </div>

                            <div>
                                <x-input-label for="position" :value="__('Position')" />
                                <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" 
                                             :value="old('position', $employee->position)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('position')" />
                            </div>

                            <div>
                                <x-input-label for="salary" :value="__('Salary')" />
                                <x-text-input id="salary" name="salary" type="number" step="0.01" class="mt-1 block w-full" 
                                             :value="old('salary', $employee->salary)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('salary')" />
                            </div>

                            <div>
                                <x-input-label for="hire_date" :value="__('Hire Date')" />
                                <x-text-input id="hire_date" name="hire_date" type="date" class="mt-1 block w-full" 
                                             :value="old('hire_date', $employee->hire_date->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('hire_date')" />
                            </div>

                            <div>
                                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" 
                                             :value="old('date_of_birth', $employee->date_of_birth->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Phone Number')" />
                                <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" 
                                             :value="old('phone', $employee->phone)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="address" :value="__('Address')" />
                                <textarea id="address" name="address" class="form-input" rows="3" required>{{ old('address', $employee->address) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('address')" />
                            </div>

                            <div>
                                <x-input-label for="photo" :value="__('Profile Photo')" />
                                <input id="photo" name="photo" type="file" class="mt-1 block w-full" accept="image/*" />
                                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                                @if($employee->photo)
                                    <p class="mt-1 text-sm text-gray-500">Current: {{ basename($employee->photo) }}</p>
                                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="Current Photo" class="w-20 h-20 rounded object-cover mt-2">
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Update Employee') }}</x-primary-button>
                            <a href="{{ route('employees.index') }}" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>