<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillType extends Model
{
    use HasFactory;
    protected $table = 'skill_types';
    protected $fillable = [
        'name',
        'rate',
    ];
}
