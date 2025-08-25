<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Performance Reviews') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Performance Reviews</h3>
                        @can('access', ['admin', 'hr_manager'])
                        <a href="{{ route('performance-reviews.create') }}" class="btn-primary">
                            Create Review
                        </a>
                        @endcan
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('performance-reviews.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                                <label for="rating" class="form-label">Rating</label>
                                <select name="rating" id="rating" class="form-input">
                                    <option value="">All Ratings</option>
                                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>Outstanding (5)</option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>Exceeds Expectations (4)</option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>Meets Expectations (3)</option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>Needs Improvement (2)</option>
                                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>Poor (1)</option>
                                </select>
                            </div>
                            <div>
                                <label for="year" class="form-label">Year</label>
                                <select name="year" id="year" class="form-input">
                                    <option value="">All Years</option>
                                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="btn-primary w-full">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Performance Reviews Table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    @can('access', ['admin', 'hr_manager'])
                                    <th>Employee</th>
                                    @endcan
                                    <th>Reviewer</th>
                                    <th>Review Date</th>
                                    <th>Rating</th>
                                    <th>Comments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        @can('access', ['admin', 'hr_manager'])
                                        <td>
                                            <div class="flex items-center">
                                                @if($review->employee->photo)
                                                    <img src="{{ asset('storage/' . $review->employee->photo) }}" 
                                                         alt="{{ $review->employee->user->name }}" 
                                                         class="w-8 h-8 rounded-full object-cover mr-2">
                                                @endif
                                                {{ $review->employee->user->name }}
                                            </div>
                                        </td>
                                        @endcan
                                        <td>{{ $review->reviewer->name }}</td>
                                        <td>{{ $review->review_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="px-2 py-1 text-xs rounded-full {{ $review->rating_class }}">
                                                {{ $review->rating_text }} ({{ $review->rating }}/5)
                                            </span>
                                        </td>
                                        <td class="max-w-xs truncate">{{ $review->comments }}</td>
                                        <td>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('performance-reviews.show', $review) }}" class="text-blue-600 hover:text-blue-900">
                                                    View
                                                </a>
                                                @can('access', ['admin', 'hr_manager'])
                                                <a href="{{ route('performance-reviews.edit', $review) }}" class="text-green-600 hover:text-green-900">
                                                    Edit
                                                </a>
                                                <form action="{{ route('performance-reviews.destroy', $review) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('Are you sure you want to delete this performance review?')">
                                                        Delete
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->isEmployee() ? 5 : 6 }}" class="text-center py-4">
                                            No performance reviews found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $reviews->links() }}
                    </div>

                    <!-- Statistics -->
                    @if($reviews->count() > 0)
                    <div class="mt-8 border-t pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Performance Statistics</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $reviews->count() }}</div>
                                <div class="text-sm text-blue-600">Total Reviews</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-green-600">{{ number_format($reviews->avg('rating'), 1) }}/5</div>
                                <div class="text-sm text-green-600">Average Rating</div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-yellow-600">
                                    {{ $reviews->where('rating', '>=', 4)->count() }}
                                </div>
                                <div class="text-sm text-yellow-600">Positive Reviews (4+)</div>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-red-600">
                                    {{ $reviews->where('rating', '<=', 2)->count() }}
                                </div>
                                <div class="text-sm text-red-600">Needs Improvement (2-)</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>