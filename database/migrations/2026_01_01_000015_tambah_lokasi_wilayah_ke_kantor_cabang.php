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
        Schema::table('kantor_cabangs', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable()->after('kedeputian_wilayah_id')->constrained('provinces')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->after('province_id')->constrained('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kantor_cabangs', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['province_id', 'city_id']);
        });
    }
};
