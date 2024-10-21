<?php

namespace App\Http\Controllers;

use App\Models\User;
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
       
        if ($user->hasRole('admin')) {
            // Fetch additional data for the admin dashboard
            $allAttendances = DB::table('attendance')->orderBy('created_at', 'desc')->get();
            $allUsers = User::count(); // Count total number of users            

            // Pass the relevant data to the admin view
            return view('admin.index', compact('allUsers'));
        }

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
        // Calculate total hours for today excluding leave
        $today = Carbon::today();
        $todayTotalHours = $attendances->filter(function ($attendance) use ($today) {
        // Exclude attendance records where `is_leave` is true
        return Carbon::parse($attendance->created_at)->isToday() && !$attendance->is_leave;
        })->sum('total_hours');


        // Calculate actual worked hours for the current week (without subtracting leave hours)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyTotalHours = $attendances->filter(function ($attendance) use ($startOfWeek, $endOfWeek) {
            return Carbon::parse($attendance->created_at)->between($startOfWeek, $endOfWeek) && !$attendance->is_leave;
        })->sum('total_hours');

        // Get number of leave days this week, but don't subtract from worked hours
        $leaveDaysThisWeek = DB::table('attendance')
            ->where('user_id', $user->id)
            ->where('is_leave', true)
            ->whereBetween('clock_in', [$startOfWeek, $endOfWeek])
            ->count();

        // Calculate actual worked hours for the current month (without subtracting leave hours)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $monthlyTotalHours = $attendances->filter(function ($attendance) use ($startOfMonth, $endOfMonth) {
            return Carbon::parse($attendance->created_at)->between($startOfMonth, $endOfMonth) && !$attendance->is_leave;
        })->sum('total_hours');

        // Get number of leave days this month
        $leaveDaysThisMonth = DB::table('attendance')
            ->where('user_id', $user->id)
            ->where('is_leave', true)
            ->whereBetween('clock_in', [$startOfMonth, $endOfMonth])
            ->count();

        // Define the total hours expected per week
        $totalHoursPerWeek = 36;

        // Calculate remaining hours left in the week, subtracting leave hours
        $leftHoursPerWeek = $totalHoursPerWeek - $weeklyTotalHours - ($leaveDaysThisWeek * 8);

        // Define the total hours expected per month
        $totalMonthlyHours = 144;

        // Calculate remaining hours left in the month, subtracting leave hours
        $leftHoursPerMonth = $totalMonthlyHours - $monthlyTotalHours - ($leaveDaysThisMonth * 8);

        // Pass the attendance records, today's total hours, weekly total hours,
        // monthly total hours, left hours per week, and left hours per month to the view
        return view('dashboard', [
            'attendances' => $attendances,
            'weekendAttendances' => $weekendAttendances,
            'todayTotalHours' => $todayTotalHours,
            'weeklyTotalHours' => $weeklyTotalHours, // Worked hours without leaves
            'monthlyTotalHours' => $monthlyTotalHours, // Worked hours without leaves
            'leftHoursPerWeek' => $leftHoursPerWeek,
            'leftHoursPerMonth' => $leftHoursPerMonth,
            'isClockedIn' => $attendances->whereNull('clock_out')->isNotEmpty(),
        ]);
    }
}
