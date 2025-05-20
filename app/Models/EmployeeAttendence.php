<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendence extends Model
{
    use HasFactory;
    protected $table = 'employee_attendence';
    protected $fillable = [
        'employee_id',
        'location_id',
        'attendance_date',
        'status',
        'notes',
        'overtime_hours'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
