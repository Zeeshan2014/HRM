<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if the user has already clocked in and hasn't clocked out yet
        $existingAttendance = DB::table('attendance')
            ->where('user_id', $user->id)
            ->whereNull('clock_out') // Look for a record without a clock_out
            ->first();

        // Pass the attendance status to the view
        return view('daily', [
            'isClockedIn' => $existingAttendance ? true : false,
        ]);
    }

    
    

    public function clockIn(Request $request)
    {
        $employee = $request->user();
        $employee->attendance()->create([
            'clock_in' => now(),
        ]);

        return redirect()->back()->with('success', 'Clocked in successfully.');
    }

    public function clockOut(Request $request)
    {
        $employee = $request->user();
        $attendance = $employee->attendance()->latest()->first();
        $attendance->update([
            'clock_out' => now(),
        ]);

        return redirect()->back()->with('success', 'Clocked out successfully.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if the user has already clocked in and hasn't clocked out yet
        $existingAttendance = DB::table('attendance')
            ->where('user_id', $user->id)
            ->whereNull('clock_out')  // Look for a record without a clock_out
            ->first();

        if ($existingAttendance) {
            // Clock Out
            $clockOutTime = Carbon::now();
            $clockInTime = Carbon::parse($existingAttendance->clock_in);

            // Calculate total hours worked
            $totalHours = $clockInTime->diffInHours($clockOutTime);

            // Update the existing attendance record
            DB::table('attendance')
                ->where('id', $existingAttendance->id)
                ->update([
                    'clock_out' => $clockOutTime,
                    'total_hours' => $totalHours,
                    'updated_at' => Carbon::now(),
                ]);

            return redirect()->back()->with('success', 'Clocked out successfully.');
        } else {
            // Clock In
            $clockInTime = Carbon::now();

            // Insert a new record for clocking in
            DB::table('attendance')->insert([
                'user_id' => $user->id,       // Storing user ID
                'clock_in' => $clockInTime,   // Storing the current time for clock-in
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Clocked in successfully.');
        }
    }

    public function weeklyTotalHours()
    {
        $user = Auth::user();
        
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Get total hours for this week excluding leave days
        $totalHours = DB::table('attendance')
            ->where('user_id', $user->id)
            ->whereBetween('clock_in', [$startOfWeek, $endOfWeek])
            ->where('is_leave', false) // Exclude leave days
            ->sum('total_hours');

        // Get number of leave days this week
        $leaveDays = DB::table('attendance')
            ->where('user_id', $user->id)
            ->whereBetween('clock_in', [$startOfWeek, $endOfWeek])
            ->where('is_leave', true) // Count leave days
            ->count();

        // Subtract 8 hours for each leave day
        $finalTotalHours = $totalHours - ($leaveDays * 8);

        return view('dashboard', compact('finalTotalHours'));
    }

    public function monthlyTotalHours()
    {
        $user = Auth::user();
        
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Get total hours for this month excluding leave days
        $totalHours = DB::table('attendance')
            ->where('user_id', $user->id)
            ->whereBetween('clock_in', [$startOfMonth, $endOfMonth])
            ->where('is_leave', false) // Exclude leave days
            ->sum('total_hours');

        // Get number of leave days this month
        $leaveDays = DB::table('attendance')
            ->where('user_id', $user->id)
            ->whereBetween('clock_in', [$startOfMonth, $endOfMonth])
            ->where('is_leave', true) // Count leave days
            ->count();

        // Subtract 8 hours for each leave day
        $finalTotalHours = $totalHours - ($leaveDays * 8);

        return view('dashboard', compact('finalTotalHours'));
    }
}
