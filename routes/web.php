<?php

use App\Http\Controllers\AdminRequestController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeekendController;
use App\Http\Controllers\WfhController;
use App\Http\Controllers\WorkFromHomeController;
use App\Models\Attendance;
use App\Models\Weekend;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestController;
use App\http\Controllers\AdminDashboardController;
use App\http\Controllers\HistoryController;

/* Route::get('/', function () {
    return view('dashboard');
}); */
Route::get('/admin-history', [HistoryController::class, 'adminview'])->name('history');
Route::get('/history', [HistoryController::class, 'userview'])->name('user-history');



// Route for admins with the "admin" role
/* Route::get('/admin-history', function () {
    return view('history');
})->middleware(['auth', 'role:admin']); */

Route::get('/', [DashboardController::class, 'view'])->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'view'])->middleware(['auth', 'verified'])->name('dashboard');

// Route to display the dashboard with weekend attendance

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware('auth')->group(function () {
    Route::post('/request/create', [RequestController::class, 'create'])->name('request.create');
});
Route::get('/requests', function () {
    return view('requests');
});

Route::get('/pending-requests', [AdminRequestController::class, 'index'])->name('admin.requests.pending');
Route::post('/admin/requests/{id}/approve', [AdminRequestController::class, 'approve'])->name('admin.requests.approve');
Route::post('/admin/requests/{id}/reject', [AdminRequestController::class, 'reject'])->name('admin.requests.reject');

Route::get('/admin-dashboard', [AdminDashboardController::class, 'view']);




/* Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminRequestController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/pending-requests', [AdminRequestController::class, 'index'])->name('admin.requests.pending');
    Route::post('/admin/requests/{id}/approve', [AdminRequestController::class, 'approve'])->name('admin.requests.approve');
}); */




Route::get('/attendance', [AttendanceController::class, 'index']);
Route::post('/attendance/clockin', [AttendanceController::class, 'clockIn']);
Route::post('/attendance/clockout', [AttendanceController::class, 'clockOut']);

Route::get('/daily', function () {
    return view('daily');
});

Route::post('/daily', [AttendanceController::class, 'store'])->name('daily');
Route::get('/daily', [AttendanceController::class, 'index'])->name('daily.index');



Route::get('/weekend', function () {
    return view('weekend');
});

Route::post('/weekend', [WeekendController::class, 'store'])->name('weekend');
Route::get('/weekend', [WeekendController::class, 'index'])->name('weekend.index');



Route::get('/workfh', function () {
    return view('wfh');
});

Route::post('/workfh', [WorkFromHomeController::class, 'store'])->name('wfh');
Route::get('/workfh', [WorkFromHomeController::class, 'index'])->name('wfh.index');



Route::get('/leaves', function () {
    return view('leaves');
});


require __DIR__ . '/auth.php';