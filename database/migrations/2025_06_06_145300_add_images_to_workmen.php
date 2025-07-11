<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workmen', function (Blueprint $table) {
            $table->longText('aadhar')->nullable();
            $table->longText('pancard')->nullable();
            $table->longText('bank_statement')->nullable();
            $table->longText('passbook')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workmen', function (Blueprint $table) {
            //
        });
    }
};
