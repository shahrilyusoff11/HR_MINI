<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceReviewController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PerformanceReview::with(['employee.user', 'reviewer']);

        if ($user->isEmployee()) {
            $query->where('employee_id', $user->employee->id);
        }

        // Filter by employee
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $reviews = $query->latest()->paginate(15);
        $employees = Employee::with('user')->get();

        return view('performance-reviews.index', compact('reviews', 'employees'));
    }

    public function create()
    {
        $employees = Employee::with('user')->get();
        return view('performance-reviews.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_date' => 'required|date',
            'rating' => 'required|integer|between:1,5',
            'comments' => 'required|string|max:2000',
            'goals' => 'required|string|max:2000',
        ]);

        PerformanceReview::create([
            'employee_id' => $request->employee_id,
            'reviewer_id' => Auth::id(),
            'review_date' => $request->review_date,
            'rating' => $request->rating,
            'comments' => $request->comments,
            'goals' => $request->goals,
        ]);

        return redirect()->route('performance-reviews.index')->with('success', 'Performance review created successfully.');
    }

    public function show(PerformanceReview $performanceReview)
    {
        $performanceReview->load(['employee.user', 'reviewer']);
        return view('performance-reviews.show', compact('performanceReview'));
    }

    public function edit(PerformanceReview $performanceReview)
    {
        $employees = Employee::with('user')->get();
        return view('performance-reviews.edit', compact('performanceReview', 'employees'));
    }

    public function update(Request $request, PerformanceReview $performanceReview)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_date' => 'required|date',
            'rating' => 'required|integer|between:1,5',
            'comments' => 'required|string|max:2000',
            'goals' => 'required|string|max:2000',
        ]);

        $performanceReview->update([
            'employee_id' => $request->employee_id,
            'review_date' => $request->review_date,
            'rating' => $request->rating,
            'comments' => $request->comments,
            'goals' => $request->goals,
        ]);

        return redirect()->route('performance-reviews.index')->with('success', 'Performance review updated successfully.');
    }

    public function destroy(PerformanceReview $performanceReview)
    {
        $performanceReview->delete();
        return redirect()->route('performance-reviews.index')->with('success', 'Performance review deleted successfully.');
    }
}