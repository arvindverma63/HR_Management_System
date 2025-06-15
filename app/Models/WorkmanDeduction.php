<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkmanDeduction extends Model
{
    use HasFactory;

    protected $table = "workmen_deductions"; // <-- typo? Should this be "workman_deductions"?

    protected $fillable = [
        'location_id',
        'type',
        'rate',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
