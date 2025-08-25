<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Performance Review') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('performance-reviews.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                            <div>
                                <x-input-label for="review_date" :value="__('Review Date')" />
                                <x-text-input id="review_date" name="review_date" type="date" class="mt-1 block w-full" 
                                             :value="old('review_date', today()->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('review_date')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="rating" :value="__('Performance Rating')" />
                                <div class="mt-1">
                                    <div class="flex items-center space-x-4">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="flex items-center">
                                                <input type="radio" name="rating" value="{{ $i }}" 
                                                       {{ old('rating') == $i ? 'checked' : '' }} 
                                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" required>
                                                <span class="ml-2 text-sm text-gray-900">
                                                    {{ $i }} - 
                                                    @if($i === 1) Poor
                                                    @elseif($i === 2) Needs Improvement
                                                    @elseif($i === 3) Meets Expectations
                                                    @elseif($i === 4) Exceeds Expectations
                                                    @else Outstanding
                                                    @endif
                                                </span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="comments" :value="__('Comments')" />
                                <textarea id="comments" name="comments" class="form-input" rows="4" required 
                                          placeholder="Provide detailed feedback on employee performance, strengths, and areas for improvement">{{ old('comments') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('comments')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="goals" :value="__('Goals & Development Plan')" />
                                <textarea id="goals" name="goals" class="form-input" rows="4" required 
                                          placeholder="Set specific goals and development objectives for the next review period">{{ old('goals') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('goals')" />
                            </div>
                        </div>

                        <!-- Rating Guide -->
                        <div class="bg-blue-50 p-4 rounded-lg mt-6">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Rating Guide</h4>
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-2 text-xs">
                                <div class="bg-red-100 p-2 rounded text-center">
                                    <strong>1 - Poor</strong><br>
                                    Does not meet expectations
                                </div>
                                <div class="bg-orange-100 p-2 rounded text-center">
                                    <strong>2 - Needs Improvement</strong><br>
                                    Partially meets expectations
                                </div>
                                <div class="bg-yellow-100 p-2 rounded text-center">
                                    <strong>3 - Meets Expectations</strong><br>
                                    Fully meets job requirements
                                </div>
                                <div class="bg-green-100 p-2 rounded text-center">
                                    <strong>4 - Exceeds Expectations</strong><br>
                                    Goes beyond job requirements
                                </div>
                                <div class="bg-blue-100 p-2 rounded text-center">
                                    <strong>5 - Outstanding</strong><br>
                                    Exceptional performance
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create Review') }}</x-primary-button>
                            <a href="{{ route('performance-reviews.index') }}" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>