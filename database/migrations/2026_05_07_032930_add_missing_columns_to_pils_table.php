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
            $table->integer('jumlah_petugas')->default(1)->after('lokasi_kegiatan');
            $table->text('lokasi_detail')->nullable()->after('jumlah_petugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pils', function (Blueprint $table) {
            $table->dropColumn(['jumlah_petugas', 'lokasi_detail']);
        });
    }
};
