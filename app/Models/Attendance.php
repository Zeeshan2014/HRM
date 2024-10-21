<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendance';
    protected $fillable = [
        'user_id', // Add user_id to the fillable array
        'date',    // Other fields (e.g., date, clock_in, clock_out)
        'clock_in',
        'clock_out',
        'status',
        'total_hours',
        'is_leave',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}