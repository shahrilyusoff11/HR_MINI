<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employees') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Employee List</h3>
                        <a href="{{ route('employees.create') }}" class="btn-primary">
                            Add New Employee
                        </a>
                    </div>

                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('employees.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="search" class="form-label">Search</label>
                                <input type="text" name="search" id="search" class="form-input" 
                                       placeholder="Search by name or email" value="{{ request('search') }}">
                            </div>
                            <div>
                                <label for="department" class="form-label">Department</label>
                                <select name="department" id="department" class="form-input">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
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

                    <!-- Employees Table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Salary</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    <tr>
                                        <td>
                                            @if($employee->photo)
                                                <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->user->name }}" 
                                                     class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-gray-600 text-sm">{{ substr($employee->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $employee->user->name }}</td>
                                        <td>{{ $employee->user->email }}</td>
                                        <td>{{ $employee->department->name }}</td>
                                        <td>{{ $employee->position }}</td>
                                        <td>${{ number_format($employee->salary, 2) }}</td>
                                        <td>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('employees.show', $employee) }}" class="text-blue-600 hover:text-blue-900">
                                                    View
                                                </a>
                                                <a href="{{ route('employees.edit', $employee) }}" class="text-green-600 hover:text-green-900">
                                                    Edit
                                                </a>
                                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('Are you sure you want to delete this employee?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">No employees found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>