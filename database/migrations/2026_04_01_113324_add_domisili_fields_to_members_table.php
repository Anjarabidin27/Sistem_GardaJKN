<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('dom_province_id')->nullable()->after('district_id')->constrained('provinces')->nullOnDelete();
            $table->foreignId('dom_city_id')->nullable()->after('dom_province_id')->constrained('cities')->nullOnDelete();
            $table->foreignId('dom_district_id')->nullable()->after('dom_city_id')->constrained('districts')->nullOnDelete();
            $table->text('dom_address_detail')->nullable()->after('dom_district_id');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['dom_province_id']);
            $table->dropForeign(['dom_city_id']);
            $table->dropForeign(['dom_district_id']);
            $table->dropColumn(['dom_province_id', 'dom_city_id', 'dom_district_id', 'dom_address_detail']);
        });
    }
};
