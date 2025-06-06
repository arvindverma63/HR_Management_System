<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $fillable = [
        'location_id',
        'name',
        'surname',
        'sex',
        'dob',
        'blood_group',
        'designation',
        'monthly_rate',
        'handicapped',
        'pan_number',
        'aadhar_number',
        'qualification',
        'mobile_number',
        'local_address',
        'emergency_contact',
        'father_name',
        'permanent_address',
        'city',
        'state',
        'phone',
        'type_of_employment',
        'identification_mark',
        'pf_uan',
        'esic_no',
        'pwjby_policy',
        'pmsby_start_date',
        'pmsby_insurance',
        'agency_number',
        'bank_ifsc',
        'bank_account',
        'medical_condition',
        'nationality',
        'nominee_name',
        'nominee_address',
        'nominee_relation',
        'nominee_phone',
        'hourly_pay',
        'refer_by',
        'aadhar',
        'pancard',
        'bank_statement',
        'passbook'
    ];


    protected $casts = [
        'dob' => 'date',
        'pmsby_start_date' => 'date',
        'handicapped' => 'boolean',
    ];


    public function location()
    {
        return $this->belongsTo(Location::class,'location_id');
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation');
    }
}
