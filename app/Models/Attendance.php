<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'workman_id',
        'location_id',
        'attendance_date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'attendance_date' => 'date',
    ];

    /**
     * Get the workman associated with the attendance.
     */
    public function workman()
    {
        return $this->belongsTo(Workman::class);
    }

    /**
     * Get the location associated with the attendance.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
