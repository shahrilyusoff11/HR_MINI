<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Process Payroll') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('payrolls.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="employee_id" :value="__('Employee')" />
                                <select id="employee_id" name="employee_id" class="form-input" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->user->name }} - {{ $employee->department->name }} (${{ number_format($employee->salary, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('employee_id')" />
                            </div>

                            <div>
                                <x-input-label for="pay_period_start" :value="__('Pay Period Start')" />
                                <x-text-input id="pay_period_start" name="pay_period_start" type="date" class="mt-1 block w-full" 
                                             :value="old('pay_period_start')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('pay_period_start')" />
                            </div>

                            <div>
                                <x-input-label for="pay_period_end" :value="__('Pay Period End')" />
                                <x-text-input id="pay_period_end" name="pay_period_end" type="date" class="mt-1 block w-full" 
                                             :value="old('pay_period_end')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('pay_period_end')" />
                            </div>

                            <div>
                                <x-input-label for="basic_salary" :value="__('Basic Salary')" />
                                <x-text-input id="basic_salary" name="basic_salary" type="number" step="0.01" class="mt-1 block w-full" 
                                             :value="old('basic_salary')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('basic_salary')" />
                            </div>

                            <div>
                                <x-input-label for="overtime" :value="__('Overtime Pay')" />
                                <x-text-input id="overtime" name="overtime" type="number" step="0.01" class="mt-1 block w-full" 
                                             :value="old('overtime', 0)" />
                                <x-input-error class="mt-2" :messages="$errors->get('overtime')" />
                            </div>

                            <div>
                                <x-input-label for="bonus" :value="__('Bonus')" />
                                <x-text-input id="bonus" name="bonus" type="number" step="0.01" class="mt-1 block w-full" 
                                             :value="old('bonus', 0)" />
                                <x-input-error class="mt-2" :messages="$errors->get('bonus')" />
                            </div>

                            <div>
                                <x-input-label for="deductions" :value="__('Deductions')" />
                                <x-text-input id="deductions" name="deductions" type="number" step="0.01" class="mt-1 block w-full" 
                                             :value="old('deductions', 0)" />
                                <x-input-error class="mt-2" :messages="$errors->get('deductions')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Notes')" />
                                <textarea id="notes" name="notes" class="form-input" rows="3">{{ old('notes') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                            </div>
                        </div>

                        <!-- Salary Calculation Preview -->
                        <div class="bg-gray-50 p-4 rounded-lg mt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Salary Calculation Preview</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-gray-600">Basic Salary:</span>
                                        <span id="basicSalaryPreview">$0.00</span>
                                    </div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-gray-600">Overtime:</span>
                                        <span id="overtimePreview">$0.00</span>
                                    </div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-gray-600">Bonus:</span>
                                        <span id="bonusPreview">$0.00</span>
                                    </div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-gray-600">Deductions:</span>
                                        <span id="deductionsPreview">$0.00</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="flex justify-between font-bold">
                                        <span class="text-gray-800">Net Salary:</span>
                                        <span id="netSalaryPreview">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Process Payroll') }}</x-primary-button>
                            <a href="{{ route('payrolls.index') }}" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const basicSalaryInput = document.getElementById('basic_salary');
            const overtimeInput = document.getElementById('overtime');
            const bonusInput = document.getElementById('bonus');
            const deductionsInput = document.getElementById('deductions');
            
            const basicSalaryPreview = document.getElementById('basicSalaryPreview');
            const overtimePreview = document.getElementById('overtimePreview');
            const bonusPreview = document.getElementById('bonusPreview');
            const deductionsPreview = document.getElementById('deductionsPreview');
            const netSalaryPreview = document.getElementById('netSalaryPreview');

            function calculateNetSalary() {
                const basicSalary = parseFloat(basicSalaryInput.value) || 0;
                const overtime = parseFloat(overtimeInput.value) || 0;
                const bonus = parseFloat(bonusInput.value) || 0;
                const deductions = parseFloat(deductionsInput.value) || 0;
                
                const netSalary = basicSalary + overtime + bonus - deductions;

                basicSalaryPreview.textContent = `$${basicSalary.toFixed(2)}`;
                overtimePreview.textContent = `$${overtime.toFixed(2)}`;
                bonusPreview.textContent = `$${bonus.toFixed(2)}`;
                deductionsPreview.textContent = `$${deductions.toFixed(2)}`;
                netSalaryPreview.textContent = `$${netSalary.toFixed(2)}`;
            }

            // Add event listeners to all input fields
            [basicSalaryInput, overtimeInput, bonusInput, deductionsInput].forEach(input => {
                input.addEventListener('input', calculateNetSalary);
            });

            // Calculate initial values
            calculateNetSalary();

            // Auto-fill basic salary when employee is selected
            const employeeSelect = document.getElementById('employee_id');
            employeeSelect.addEventListener('change', function() {
                const employeeId = this.value;
                if (employeeId) {
                    fetch(`/employees/${employeeId}/salary`)
                        .then(response => response.json())
                        .then(data => {
                            basicSalaryInput.value = data.salary;
                            calculateNetSalary();
                        })
                        .catch(error => {
                            console.error('Error fetching employee salary:', error);
                        });
                }
            });
        });
    </script>
</x-app-layout>