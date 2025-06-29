<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAddition extends Model
{
    use HasFactory;
    protected $table = 'employee_additions';
    protected $fillable = [
        'type',
        'rate',
        'employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
