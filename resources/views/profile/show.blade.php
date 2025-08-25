<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- User Information -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Name</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Email</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Role</label>
                                    <p class="mt-1 text-sm text-gray-900 capitalize">{{ auth()->user()->role }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Photo -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Photo</h3>
                            @if(auth()->user()->employee && auth()->user()->employee->photo)
                                <img src="{{ asset('storage/' . auth()->user()->employee->photo) }}" alt="Profile Photo" class="w-32 h-32 rounded-full object-cover mx-auto">
                            @else
                                <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center mx-auto">
                                    <span class="text-gray-600 text-2xl">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Employee Information -->
                    @if(auth()->user()->employee)
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Employee Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Position</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->employee->position }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Department</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->employee->department->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Salary</label>
                                    <p class="mt-1 text-sm text-gray-900">${{ number_format(auth()->user()->employee->salary, 2) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Hire Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->employee->hire_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Phone</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->employee->phone }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Address</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->employee->address }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 border-t pt-6">
                        <div class="flex space-x-4">
                            <a href="{{ route('profile.edit') }}" class="btn-primary">
                                Edit Profile
                            </a>
                            @if(auth()->user()->employee)
                                <a href="{{ route('employees.show', auth()->user()->employee) }}" class="btn-secondary">
                                    View Employee Details
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>