<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'pay_period_start',
        'pay_period_end',
        'basic_salary',
        'overtime',
        'bonus',
        'deductions',
        'net_salary',
        'status',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'overtime' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function getPayPeriodAttribute()
    {
        return $this->pay_period_start->format('M d, Y') . ' - ' . $this->pay_period_end->format('M d, Y');
    }

    public function getGrossSalaryAttribute()
    {
        return $this->basic_salary + $this->overtime + $this->bonus;
    }

    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    public function getIsProcessedAttribute()
    {
        return $this->status === 'processed';
    }

    public function getIsPaidAttribute()
    {
        return $this->status === 'paid';
    }
}