<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payroll Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Payroll Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payroll Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Employee</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $payroll->employee->user->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Pay Period</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $payroll->pay_period }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Status</label>
                                    <p class="mt-1">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $payroll->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $payroll->status === 'processed' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $payroll->status === 'pending' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($payroll->status) }}
                                        </span>
                                    </p>
                                </div>
                                @if($payroll->payment_date)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Payment Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $payroll->payment_date->format('M d, Y') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Salary Breakdown -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Salary Breakdown</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Basic Salary:</span>
                                        <span class="font-medium">${{ number_format($payroll->basic_salary, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Overtime Pay:</span>
                                        <span class="font-medium">${{ number_format($payroll->overtime, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Bonus:</span>
                                        <span class="font-medium">${{ number_format($payroll->bonus, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Deductions:</span>
                                        <span class="font-medium text-red-600">-${{ number_format($payroll->deductions, 2) }}</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="flex justify-between font-bold text-lg">
                                        <span class="text-gray-800">Net Salary:</span>
                                        <span class="text-green-600">${{ number_format($payroll->net_salary, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($payroll->notes)
                    <div class="mt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Notes</h4>
                        <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $payroll->notes }}</p>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-8 border-t pt-6">
                        <div class="flex space-x-4">
                            <a href="{{ route('payrolls.index') }}" class="btn-secondary">
                                Back to List
                            </a>
                            
                            @can('access', ['admin', 'hr_manager'])
                            <a href="{{ route('payrolls.payslip', $payroll) }}" class="btn-primary">
                                Download Payslip
                            </a>
                            @if($payroll->status === 'processed')
                                <form action="{{ route('payrolls.mark-paid', $payroll) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn-success">
                                        Mark as Paid
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('payrolls.edit', $payroll) }}" class="btn-primary">
                                Edit Payroll
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>