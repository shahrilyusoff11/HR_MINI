<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Departments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Department List</h3>
                        <a href="{{ route('departments.create') }}" class="btn-primary">
                            Add New Department
                        </a>
                    </div>

                    <!-- Departments Table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Employees</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments as $department)
                                    <tr>
                                        <td class="font-medium">{{ $department->name }}</td>
                                        <td class="max-w-xs truncate">{{ $department->description ?? 'No description' }}</td>
                                        <td>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                {{ $department->employees_count }} employees
                                            </span>
                                        </td>
                                        <td>{{ $department->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('departments.show', $department) }}" class="text-blue-600 hover:text-blue-900">
                                                    View
                                                </a>
                                                <a href="{{ route('departments.edit', $department) }}" class="text-green-600 hover:text-green-900">
                                                    Edit
                                                </a>
                                                <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('Are you sure you want to delete this department?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No departments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $departments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>