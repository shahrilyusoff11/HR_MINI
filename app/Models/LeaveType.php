<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'days_per_year',
        'description',
    ];

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function getUsedDaysAttribute()
    {
        return $this->leaves()->where('status', 'approved')->sum('days');
    }

    public function getAvailableDaysAttribute()
    {
        return $this->days_per_year - $this->used_days;
    }
}