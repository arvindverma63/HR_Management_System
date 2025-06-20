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
            $table->decimal('basic_pay', 10, 2)->nullable();
            $table->decimal('house_rent_allowance', 10, 2)->nullable();
            $table->decimal('conveyance_allowance', 10, 2)->nullable();
            $table->decimal('food_allowance', 10, 2)->nullable();
            $table->decimal('site_allowance', 10, 2)->nullable();
            $table->decimal('statutory_bonus', 10, 2)->nullable();
            $table->decimal('retrenchment_allowance', 10, 2)->nullable();
            $table->decimal('medical', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workmen', function (Blueprint $table) {
            $table->dropColumn([
                'basic_pay',
                'house_rent_allowance',
                'conveyance_allowance',
                'food_allowance',
                'site_allowance',
                'statutory_bonus',
                'retrenchment_allowance',
                'medical',
            ]);
        });
    }
};
