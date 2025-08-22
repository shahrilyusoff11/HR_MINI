<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'review_date',
        'rating',
        'comments',
        'goals',
    ];

    protected $casts = [
        'review_date' => 'date',
        'rating' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function getRatingTextAttribute()
    {
        $ratings = [
            1 => 'Poor',
            2 => 'Needs Improvement',
            3 => 'Meets Expectations',
            4 => 'Exceeds Expectations',
            5 => 'Outstanding'
        ];

        return $ratings[$this->rating] ?? 'Not Rated';
    }

    public function getRatingClassAttribute()
    {
        $classes = [
            1 => 'text-red-600 bg-red-100',
            2 => 'text-orange-600 bg-orange-100',
            3 => 'text-yellow-600 bg-yellow-100',
            4 => 'text-green-600 bg-green-100',
            5 => 'text-blue-600 bg-blue-100'
        ];

        return $classes[$this->rating] ?? 'text-gray-600 bg-gray-100';
    }
}