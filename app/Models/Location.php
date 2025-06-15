<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'contact_number',
        'start_shift_time',
        'end_shift_time',
    ];

    public function workmanDeduction()
    {
        return $this->hasMany(WorkmanDeduction::class);
    }

    public function employeeDeduction()
    {
        return $this->hasMany(EmployeeDeduction::class);
    }
}
