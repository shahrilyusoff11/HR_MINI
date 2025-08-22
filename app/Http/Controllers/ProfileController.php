<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    public function updateEmployeeProfile(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        $photoPath = $employee->photo;
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $photoPath = $request->file('photo')->store('employee-photos', 'public');
        }

        $employee->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'photo' => $photoPath,
        ]);

        return redirect()->route('profile.show')->with('success', 'Employee profile updated successfully.');
    }
}