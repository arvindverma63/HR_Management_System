<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeduction extends Model
{
    use HasFactory;
    protected $table = "employee_deductions";
    protected $fillable = [
        'employee_id',
        'type',
        'rate',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
