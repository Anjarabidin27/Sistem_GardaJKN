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
        Schema::table('pils', function (Blueprint $table) {
            $table->smallInteger('count_seg_pbpu')->default(0)->after('rata_nps_rekomendasi_bpjs');
            $table->smallInteger('count_seg_bp')->default(0)->after('count_seg_pbpu');
            $table->smallInteger('count_seg_ppu_bu')->default(0)->after('count_seg_bp');
            $table->smallInteger('count_seg_ppu_pem')->default(0)->after('count_seg_ppu_bu');
            $table->smallInteger('count_seg_pbi_apbn')->default(0)->after('count_seg_ppu_pem');
            $table->smallInteger('count_seg_pbi_apbd')->default(0)->after('count_seg_pbi_apbn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pils', function (Blueprint $table) {
            $table->dropColumn([
                'count_seg_pbpu', 'count_seg_bp', 'count_seg_ppu_bu', 
                'count_seg_ppu_pem', 'count_seg_pbi_apbn', 'count_seg_pbi_apbd'
            ]);
        });
    }
};
