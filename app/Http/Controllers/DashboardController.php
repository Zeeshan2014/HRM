<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function view()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch attendance records for the user
        $attendances = DB::table('attendance')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc') // Optional: order by the most recent first
            ->get();

        // Fetch weekend attendance records for the user
        $weekendAttendances = DB::table('weekend')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc') // Optional: order by the most recent first
            ->get();

        // Calculate total hours for today
        $today = Carbon::today();
        $todayTotalHours = 0;

        foreach ($attendances as $attendance) {
            $attendanceDate = Carbon::parse($attendance->created_at);
            if ($attendanceDate->isToday()) {
                if ($attendance->clock_out) {
                    // If clock out is available, calculate total hours between clock_in and clock_out
                    $clockIn = Carbon::parse($attendance->clock_in);
                    $clockOut = Carbon::parse($attendance->clock_out);
                    $todayTotalHours += $clockIn->diffInMinutes($clockOut) / 60;
                } elseif ($attendance->clock_in) {
                    // If clock out is missing, calculate time from clock_in to now
                    $clockIn = Carbon::parse($attendance->clock_in);
                    $todayTotalHours += $clockIn->diffInMinutes(Carbon::now()) / 60;
                }
            }
        }

        // Calculate total hours for the current week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyTotalHours = 0;
        foreach ($attendances as $attendance) {
            $attendanceDate = Carbon::parse($attendance->created_at);
            if ($attendanceDate->between($startOfWeek, $endOfWeek)) {
                if ($attendance->clock_out) {
                    // Calculate weekly total hours if clock out is available
                    $clockIn = Carbon::parse($attendance->clock_in);
                    $clockOut = Carbon::parse($attendance->clock_out);
                    $weeklyTotalHours += $clockIn->diffInMinutes($clockOut) / 60;
                } elseif ($attendance->clock_in) {
                    // If clock out is missing, calculate up to now
                    $clockIn = Carbon::parse($attendance->clock_in);
                    $weeklyTotalHours += $clockIn->diffInMinutes(Carbon::now()) / 60;
                }
            }
        }

        // Calculate total hours for the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $monthlyTotalHours = 0;
        foreach ($attendances as $attendance) {
            $attendanceDate = Carbon::parse($attendance->created_at);
            if ($attendanceDate->between($startOfMonth, $endOfMonth)) {
                if ($attendance->clock_out) {
                    // Calculate monthly total hours if clock out is available
                    $clockIn = Carbon::parse($attendance->clock_in);
                    $clockOut = Carbon::parse($attendance->clock_out);
                    $monthlyTotalHours += $clockIn->diffInMinutes($clockOut) / 60;
                } elseif ($attendance->clock_in) {
                    // If clock out is missing, calculate up to now
                    $clockIn = Carbon::parse($attendance->clock_in);
                    $monthlyTotalHours += $clockIn->diffInMinutes(Carbon::now()) / 60;
                }
            }
        }

        // Define the total hours expected per week
        $totalHoursPerWeek = 36;

        // Calculate remaining hours left in the week
        $leftHoursPerWeek = $totalHoursPerWeek - $weeklyTotalHours;

        // Define the total hours expected per month
        $totalMonthlyHours = 144;

        // Calculate remaining hours left in the month
        $leftHoursPerMonth = $totalMonthlyHours - $monthlyTotalHours;

        // Pass the attendance records, today's total hours, weekly total hours,
        // monthly total hours, left hours per week, and left hours per month to the view
        return view('dashboard', [
            'attendances' => $attendances,
            'weekendAttendances' => $weekendAttendances,
            'todayTotalHours' => $todayTotalHours,
            'weeklyTotalHours' => $weeklyTotalHours,
            'monthlyTotalHours' => $monthlyTotalHours,
            'leftHoursPerWeek' => $leftHoursPerWeek,
            'leftHoursPerMonth' => $leftHoursPerMonth,
            'isClockedIn' => $attendances->whereNull('clock_out')->isNotEmpty(),
        ]);
    }
}
