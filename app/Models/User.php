<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isHRManager()
    {
        return $this->role === 'hr_manager';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    public function leavesApproved()
    {
        return $this->hasMany(Leave::class, 'approved_by');
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class, 'reviewer_id');
    }
}