<?php

namespace App\Http\Controllers;

use App\Models\Request; // Import the Request model
use Illuminate\Http\Request as HttpRequest; // Import for handling HTTP requests if needed

class HistoryController extends Controller
{
    public function adminview() {
        if (!auth()->user()->hasRole('admin')) {
            abort(403); // Access denied
        }

        // Fetch requests that are either accepted or rejected
        $requests = Request::whereIn('status', ['approved', 'rejected'])->get();

        // Pass the requests to the view
        return view('admin.history', ['requests' => $requests]);
    }
    public function userview() {
        if (!auth()->user()->hasRole('user')) {
            abort(403); // Access denied
        }
    
        // Fetch approved, rejected, or pending requests for the authenticated user
        $requests = Request::where('user_id', auth()->id())
                            ->whereIn('status', ['approved', 'rejected', 'pending'])
                            ->get();
    
        // Pass the requests to the view
        return view('history', ['requests' => $requests]);
    }
    
    
}
