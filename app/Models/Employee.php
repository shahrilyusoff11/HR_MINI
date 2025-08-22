<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'position',
        'salary',
        'hire_date',
        'date_of_birth',
        'phone',
        'address',
        'photo',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'date_of_birth' => 'date',
        'salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class);
    }

    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function getEmailAttribute()
    {
        return $this->user->email;
    }

    public function getAgeAttribute()
    {
        return now()->diffInYears($this->date_of_birth);
    }

    public function getYearsOfServiceAttribute()
    {
        return now()->diffInYears($this->hire_date);
    }

    public function getCurrentMonthAttendance()
    {
        return $this->attendances()
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->get();
    }

    public function getApprovedLeavesCount($leaveTypeId = null)
    {
        $query = $this->leaves()->where('status', 'approved');
        
        if ($leaveTypeId) {
            $query->where('leave_type_id', $leaveTypeId);
        }
        
        return $query->sum('days');
    }
}