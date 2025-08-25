<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Performance Review Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Review Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Review Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Employee</label>
                                    <div class="flex items-center mt-1">
                                        @if($performanceReview->employee->photo)
                                            <img src="{{ asset('storage/' . $performanceReview->employee->photo) }}" 
                                                 alt="{{ $performanceReview->employee->user->name }}" 
                                                 class="w-10 h-10 rounded-full object-cover mr-3">
                                        @endif
                                        <div>
                                            <p class="text-sm text-gray-900">{{ $performanceReview->employee->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $performanceReview->employee->position }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Reviewer</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $performanceReview->reviewer->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Review Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $performanceReview->review_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Rating</label>
                                    <p class="mt-1">
                                        <span class="px-3 py-1 text-sm rounded-full {{ $performanceReview->rating_class }}">
                                            {{ $performanceReview->rating_text }} ({{ $performanceReview->rating }}/5)
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Rating Distribution -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Performance Insights</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Rating Distribution</label>
                                    <div class="space-y-2">
                                        @for($i = 5; $i >= 1; $i--)
                                            @php
                                                $percentage = ($performanceReview->rating == $i) ? 100 : 0;
                                                $bgColor = [
                                                    1 => 'bg-red-500',
                                                    2 => 'bg-orange-500',
                                                    3 => 'bg-yellow-500',
                                                    4 => 'bg-green-500',
                                                    5 => 'bg-blue-500'
                                                ][$i];
                                            @endphp
                                            <div class="flex items-center">
                                                <span class="w-16 text-sm text-gray-600">{{ $i }} - {{ [
                                                    1 => 'Poor',
                                                    2 => 'Needs Imp.',
                                                    3 => 'Meets Exp.',
                                                    4 => 'Exceeds Exp.',
                                                    5 => 'Outstanding'
                                                ][$i] }}</span>
                                                <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                                    <div class="{{ $bgColor }} h-2 rounded-full" 
                                                         style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="w-8 text-sm text-gray-600 text-right">
                                                    {{ $performanceReview->rating == $i ? '✓' : '' }}
                                                </span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="mt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Performance Comments</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $performanceReview->comments }}</p>
                        </div>
                    </div>

                    <!-- Goals -->
                    <div class="mt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Goals & Development Plan</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $performanceReview->goals }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 border-t pt-6">
                        <div class="flex space-x-4">
                            <a href="{{ route('performance-reviews.index') }}" class="btn-secondary">
                                Back to List
                            </a>
                            
                            @can('access', ['admin', 'hr_manager'])
                            <a href="{{ route('performance-reviews.edit', $performanceReview) }}" class="btn-primary">
                                Edit Review
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>