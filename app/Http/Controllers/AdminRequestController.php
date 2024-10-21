<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Request as UserRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use App\Notifications\RequestAccepted; // Import the notification class
use App\Models\User;
use App\Notifications\RequestRejected;

class AdminRequestController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403); // Access denied
        }

        // Fetch pending requests
        $requests = UserRequest::where('status', 'pending' )->get();
        
        // Pass the requests to the view
        return view('admin.dashboard', compact('requests'));
    }
    public function view()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403); // Access denied
        }

        // Fetch pending requests
        $requests = UserRequest::where('status', 'pending')->get();
        
        // Pass the requests to the view
        return view('admin.index', compact('requests'));
    }

    public function approve($id)
    {
        $requestEntry = \App\Models\Request::find($id);
        if ($requestEntry) {
            // Mark the request as approved
            $requestEntry->update(['status' => 'approved']);

            // After approval, perform additional actions based on the request type
            switch ($requestEntry->type) {
                case 'weekend':
                case 'wfh':
                case 'overtime':
                    // Add the hours worked to the attendance log
                    Attendance::create([
                        'user_id' => $requestEntry->user_id,
                        'clock_in' => $requestEntry->start_time,
                        'clock_out' => $requestEntry->end_time,
                        'total_hours' => $requestEntry->total_hours,
                        'is_weekend' => $requestEntry->type === 'weekend',
                    ]);
                    break;

                case 'meeting':
                    // Log meeting hours
                    Attendance::create([
                        'user_id' => $requestEntry->user_id,
                        'clock_in' => $requestEntry->start_time,
                        'clock_out' => $requestEntry->end_time,
                        'total_hours' => $requestEntry->total_hours,
                        'is_meeting' => true,
                    ]);

                    break;

                case 'leave':
                    // deduct leave hours from the user's leave balance
                    $attendance = Attendance::create([
                        'user_id' => $requestEntry->user_id,
                        'clock_in' => $requestEntry->start_time,
                        'clock_out' => $requestEntry->end_time,
                        'total_hours' => $requestEntry->total_hours,
                        'is_leave' => true,
                    ]);

                    // Calculate the difference in hours between clock_in and clock_out
                    $clockOut = Carbon::parse($attendance->clock_out);
                    $clockIn = Carbon::parse($attendance->clock_in);
                    $hoursWorked = $clockIn->diffInHours($clockOut);

                    $attendance->total_hours = $hoursWorked - 8; // Deduct 8 hours for a day of leave
                    $attendance->save();
                    
                     
                     
                     
                    break;
            }

            // Notify the user of approval
            $user = User::find($requestEntry->user_id);
            if ($user) {
                $user->notify(new RequestAccepted($requestEntry)); // Send the notification
            }

            return redirect()->back()->with('success', 'Request approved, processed, and user notified.');
        }

        return redirect()->back()->with('error', 'Request not found.');
    }

    public function reject($id)
    {
        $requestEntry = \App\Models\Request::find($id);
        if ($requestEntry) {
            // Mark the request as rejected
            $requestEntry->update(['status' => 'rejected']);

            // After rejection, perform additional actions based on the request type
            switch ($requestEntry->type) {
                case 'weekend':
                case 'wfh':
                case 'overtime':
                    // Notify the user of rejection
                    break;

                case 'meeting':
                    // Notify the user of rejection
                    break;

                case 'leave':
                    // Notify the user of rejection
                    break;
            }

            // Notify the user of rejection
            $user = User::find($requestEntry->user_id);
            if ($user) {
                $user->notify(new RequestRejected($requestEntry)); // Send the notification
            }

            return redirect()->back()->with('success', 'Request rejected and user notified.');
        }

        return redirect()->back()->with('error', 'Request not found.');
    }
}