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
        Schema::table('bpjs_keliling_participants', function (Blueprint $table) {
            $table->tinyInteger('nps_ketertarikan')->default(0)->after('suara_pelanggan');
            $table->tinyInteger('nps_rekomendasi_program')->default(0)->after('nps_ketertarikan');
            $table->tinyInteger('nps_rekomendasi_bpjs')->default(0)->after('nps_rekomendasi_program');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bpjs_keliling_participants', function (Blueprint $table) {
            $table->dropColumn(['nps_ketertarikan', 'nps_rekomendasi_program', 'nps_rekomendasi_bpjs']);
        });
    }
};
