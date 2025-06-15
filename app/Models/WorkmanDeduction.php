<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkmanDeduction extends Model
{
    use HasFactory;

    protected $table = "workmen_deductions"; // <-- typo? Should this be "workman_deductions"?

    protected $fillable = ['workman_id', 'type', 'rate'];

    public function workman()
    {
        return $this->belongsTo(Workman::class);
    }
}
