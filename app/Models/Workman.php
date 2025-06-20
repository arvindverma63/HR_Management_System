<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workman extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
        'email',
        'aadhar',
        'pancard',
        'bank_statement',
        'passbook',
        'other',
        'da',
        'workman_unique_id',

        // ✅ New allowance columns
        'basic_pay',
        'house_rent_allowance',
        'conveyance_allowance',
        'food_allowance',
        'site_allowance',
        'statutory_bonus',
        'retrenchment_allowance',
        'medical',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'dob' => 'date',
        'pmsby_start_date' => 'date',
        'handicapped' => 'boolean',
    ];

    /**
     * Get the location that the workman belongs to.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation');
    }


    public function deductions()
    {
        return $this->hasMany(WorkmanDeduction::class, 'workman_id');
    }
}
