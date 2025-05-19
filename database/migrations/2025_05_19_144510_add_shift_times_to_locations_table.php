<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShiftTimesToLocationsTable extends Migration
{
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->time('start_shift_time')->nullable()->after('contact_number');
            $table->time('end_shift_time')->nullable()->after('start_shift_time');
        });
    }

    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['start_shift_time', 'end_shift_time']);
        });
    }
}
