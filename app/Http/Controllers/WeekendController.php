<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Weekend;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WeekendController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $existingAttendance = Weekend::where('user_id', $user->id)->whereNull('clock_out')->first();
      
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
        
        $today = Carbon::now();
        $isWeekend = $today->isWeekend();

        if (!$isWeekend) {
            return redirect()->back()->with('error', 'Weekend attendance can only be recorded on weekends.');
        }

        // Check if the user has already clocked in and hasn't clocked out for weekend work
        $existingAttendance = Weekend::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->where('is_weekend', true)  // Check if it's a weekend record
            ->first();

        if ($existingAttendance) {
            // Clock Out for weekend work
            $clockOutTime = Carbon::now();
            $clockInTime = Carbon::parse($existingAttendance->clock_in);

            // Calculate total weekend hours worked
            $totalMinutes = $clockInTime->diffInHours($clockOutTime);

            // Update the existing weekend attendance record
            DB::table('weekend')
            ->where('id', $existingAttendance->id)
            ->update([
                'clock_out' => $clockOutTime,
                'total_hours' => $totalMinutes,
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Weekend Clocked out successfully.');
        } else {
            // Clock In for weekend work
            $clockInTime = Carbon::now();

            // Insert a new record for weekend clock-in
            Weekend::insert([
                'user_id' => $user->id,
                'clock_in' => $clockInTime,
                'is_weekend' => true,  // Mark as weekend attendance
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Weekend Clocked in successfully.');
        }
    }
    
}
