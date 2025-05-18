<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workman_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('restrict');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'leave']);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique constraint to prevent multiple attendance records for the same workman on the same date
            $table->unique(['workman_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
