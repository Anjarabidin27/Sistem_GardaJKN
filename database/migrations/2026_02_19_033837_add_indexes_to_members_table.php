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
        Schema::table('members', function (Blueprint $table) {
            // NFR: Statistik Dashboard Kinerja
            $table->index('created_at');
            $table->index('gender');
            $table->index('education');
            $table->index('occupation');

            // NFR: Filter Wilayah
            $table->index(['province_id', 'city_id', 'district_id'], 'idx_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['gender']);
            $table->dropIndex(['education']);
            $table->dropIndex(['occupation']);
            $table->dropIndex('idx_location');
        });
    }
};
