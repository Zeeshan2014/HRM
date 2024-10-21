<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function view()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            // Fetch all users
            $allUsers = User::all();

            // Get the start and end of the current week and month
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            // Fetch total weekly and monthly attendance hours for each user
            $userAttendance = DB::table('attendance')
                ->select('user_id', 
                    DB::raw('SUM(CASE WHEN created_at BETWEEN "' . $startOfWeek . '" AND "' . $endOfWeek . '" THEN total_hours ELSE 0 END) as weekly_hours'),
                    DB::raw('SUM(CASE WHEN created_at BETWEEN "' . $startOfMonth . '" AND "' . $endOfMonth . '" THEN total_hours ELSE 0 END) as monthly_hours')
                )
                ->groupBy('user_id')
                ->get();

            // Define total expected hours per week and month
            $totalHoursPerWeek = 36;
            $totalHoursPerMonth = 144;

            // Map attendance data to user data
            $usersWithAttendance = $allUsers->map(function($user) use ($userAttendance, $totalHoursPerWeek, $totalHoursPerMonth) {
                $attendance = $userAttendance->firstWhere('user_id', $user->id);
                $user->weekly_hours = $attendance ? $attendance->weekly_hours : 0;
                $user->monthly_hours = $attendance ? $attendance->monthly_hours : 0;
                $user->remaining_weekly_hours = $totalHoursPerWeek - $user->weekly_hours;
                $user->remaining_monthly_hours = $totalHoursPerMonth - $user->monthly_hours;
                return $user;
            });

            // Pass the relevant data to the admin view
            return view('admin.index', compact('usersWithAttendance'));
        }

        // If not admin, redirect or show a different view (as needed)
        return redirect()->route('dashboard');
    }
}