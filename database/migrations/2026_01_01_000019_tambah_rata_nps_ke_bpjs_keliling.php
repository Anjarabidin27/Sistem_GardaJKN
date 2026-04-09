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
        Schema::table('bpjs_kelilings', function (Blueprint $table) {
            $table->decimal('rata_nps_ketertarikan', 5, 2)->default(0)->after('kepuasan_tidak_puas');
            $table->decimal('rata_nps_rekomendasi_program', 5, 2)->default(0)->after('rata_nps_ketertarikan');
            $table->decimal('rata_nps_rekomendasi_bpjs', 5, 2)->default(0)->after('rata_nps_rekomendasi_program');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bpjs_kelilings', function (Blueprint $table) {
            $table->dropColumn(['rata_nps_ketertarikan', 'rata_nps_rekomendasi_program', 'rata_nps_rekomendasi_bpjs']);
        });
    }
};
