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
        Schema::table('admin_users', function (Blueprint $table) {
            $table->string('kedeputian_wilayah')->nullable()->after('role');
            $table->string('kantor_cabang')->nullable()->after('kedeputian_wilayah');
            $table->string('zona_waktu')->default('WIB')->after('kantor_cabang'); // WIB, WITA, WIT
        });
    }

    public function down(): void
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropColumn(['kedeputian_wilayah', 'kantor_cabang', 'zona_waktu']);
        });
    }
};
