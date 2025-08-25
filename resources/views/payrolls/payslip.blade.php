<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payroll->employee->user->name }} - {{ $payroll->pay_period }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 800px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .company-name { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .payslip-title { font-size: 20px; margin-bottom: 10px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .info-item { margin-bottom: 8px; }
        .info-label { font-weight: bold; color: #666; }
        .salary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .salary-table th, .salary-table td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .salary-table th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #f8f9fa; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #666; }
        .signature { margin-top: 60px; border-top: 1px solid #333; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">HR Management System</div>
            <div class="payslip-title">PAYSLIP</div>
            <div>Pay Period: {{ $payroll->pay_period }}</div>
            <div>Issue Date: {{ now()->format('M d, Y') }}</div>
        </div>

        <!-- Employee Information -->
        <div class="section">
            <div class="section-title">Employee Information</div>
            <div class="info-grid">
                <div>
                    <div class="info-item">
                        <span class="info-label">Employee Name:</span>
                        {{ $payroll->employee->user->name }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Employee ID:</span>
                        #{{ $payroll->employee->id }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Department:</span>
                        {{ $payroll->employee->department->name }}
                    </div>
                </div>
                <div>
                    <div class="info-item">
                        <span class="info-label">Position:</span>
                        {{ $payroll->employee->position }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Pay Date:</span>
                        {{ $payroll->payment_date ? $payroll->payment_date->format('M d, Y') : 'Pending' }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        {{ ucfirst($payroll->status) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings -->
        <div class="section">
            <div class="section-title">Earnings</div>
            <table class="salary-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic Salary</td>
                        <td class="text-right">${{ number_format($payroll->basic_salary, 2) }}</td>
                    </tr>
                    @if($payroll->overtime > 0)
                    <tr>
                        <td>Overtime Pay</td>
                        <td class="text-right">${{ number_format($payroll->overtime, 2) }}</td>
                    </tr>
                    @endif
                    @if($payroll->bonus > 0)
                    <tr>
                        <td>Bonus</td>
                        <td class="text-right">${{ number_format($payroll->bonus, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td>Total Earnings</td>
                        <td class="text-right">${{ number_format($payroll->basic_salary + $payroll->overtime + $payroll->bonus, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Deductions -->
        <div class="section">
            <div class="section-title">Deductions</div>
            <table class="salary-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @if($payroll->deductions > 0)
                    <tr>
                        <td>Deductions</td>
                        <td class="text-right">-${{ number_format($payroll->deductions, 2) }}</td>
                    </tr>
                    @else
                    <tr>
                        <td>No deductions</td>
                        <td class="text-right">$0.00</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Net Pay -->
        <div class="section">
            <table class="salary-table">
                <tbody>
                    <tr class="total-row">
                        <td><strong>NET PAY</strong></td>
                        <td class="text-right"><strong>${{ number_format($payroll->net_salary, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Notes -->
        @if($payroll->notes)
        <div class="section">
            <div class="section-title">Notes</div>
            <p>{{ $payroll->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="signature">
                <div style="float: left; width: 45%;">
                    <div>Employee Signature</div>
                    <div style="margin-top: 40px;">_________________________</div>
                    <div>Date: ___________________</div>
                </div>
                <div style="float: right; width: 45%;">
                    <div>Authorized Signature</div>
                    <div style="margin-top: 40px;">_________________________</div>
                    <div>Date: ___________________</div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <p>This is computer generated payslip and does not require signature.</p>
            <p>HR Management System - {{ config('app.url') }}</p>
        </div>
    </div>
</body>
</html>