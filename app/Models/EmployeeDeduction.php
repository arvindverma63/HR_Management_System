<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeduction extends Model
{
    use HasFactory;
    protected $table = "employee_deductions";
    protected $fillable = [
        'employee_unique_id',
        'type',
        'rate',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_unique_id', 'employee_unique_id');
    }
}
