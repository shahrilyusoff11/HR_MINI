<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getHoursWorkedAttribute()
    {
        if ($this->check_in && $this->check_out) {
            return $this->check_out->diffInHours($this->check_in);
        }
        
        return 0;
    }

    public function getIsLateAttribute()
    {
        if (!$this->check_in) {
            return false;
        }

        $lateTime = \Carbon\Carbon::createFromTimeString('09:15:00');
        return $this->check_in->gt($lateTime);
    }

    public function getIsEarlyLeaveAttribute()
    {
        if (!$this->check_out) {
            return false;
        }

        $earlyLeaveTime = \Carbon\Carbon::createFromTimeString('17:00:00');
        return $this->check_out->lt($earlyLeaveTime);
    }
}