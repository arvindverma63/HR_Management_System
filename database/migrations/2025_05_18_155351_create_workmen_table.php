<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkmenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workmen', function (Blueprint $table) {
            $table->id();
            // Foreign key to locations table
            $table->foreignId('location_id')->constrained()->onDelete('restrict');
            // Personal Details
            $table->string('name');
            $table->string('surname');
            $table->enum('sex', ['male', 'female'])->nullable();
            $table->date('dob')->nullable();
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->enum('designation', ['HSW', 'SSW', 'USW'])->nullable();
            $table->decimal('monthly_rate', 10, 2)->nullable();
            $table->boolean('handicapped')->nullable();
            // Identification
            $table->string('pan_number')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->string('qualification')->nullable();
            $table->string('mobile_number')->nullable();
            // Contact Information
            $table->text('local_address')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('father_name')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('phone')->nullable();
            // Employment Details
            $table->string('type_of_employment')->nullable();
            $table->string('identification_mark')->nullable();
            // Financial & Insurance Details
            $table->string('pf_uan')->nullable();
            $table->string('esic_no')->nullable();
            $table->string('pwjby_policy')->nullable();
            $table->date('pmsby_start_date')->nullable();
            $table->string('pmsby_insurance')->nullable();
            $table->string('agency_number')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('bank_account')->nullable();
            // Nominee Details
            $table->string('medical_condition')->nullable();
            $table->string('nationality')->nullable();
            $table->string('nominee_name')->nullable();
            $table->text('nominee_address')->nullable();
            $table->string('nominee_relation')->nullable();
            $table->string('nominee_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workmen');
    }
}
