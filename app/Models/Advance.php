<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    use HasFactory;
    protected $table = 'advances';
    protected $fillable = [
        'employee_id',
        'money',
        'notes',
        'status',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
