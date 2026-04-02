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
        // 1. Update Header (Activities)
        Schema::table('bpjs_kelilings', function (Blueprint $table) {
            $table->string('kedeputian_wilayah')->nullable()->after('jam_selesai');
            $table->string('kantor_cabang')->nullable()->after('kedeputian_wilayah');
            $table->enum('kuadran', ['1- Engagement', '2- Rekrutmen', '3- Pembaharuan Data', '4- Iuran'])->nullable()->after('kantor_cabang');
            $table->string('nama_frontliner')->nullable()->after('kuadran');
            $table->string('lokasi_kegiatan')->nullable()->after('nama_frontliner');
            $table->string('zona_waktu')->nullable()->after('lokasi_kegiatan');
            // Update enum or use string for more flexibility
            $table->string('jenis_kegiatan')->change(); 
        });

        // 2. Create Detail (Participants Served)
        Schema::create('bpjs_keliling_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bpjs_keliling_id')->constrained('bpjs_kelilings')->cascadeOnDelete();
            $table->string('nik', 16);
            $table->string('name')->nullable();
            $table->string('segmen_peserta')->nullable(); // PBPU, BP, PPU BU, etc.
            $table->string('phone_number')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('jenis_layanan')->nullable(); // Administrasi, Informasi, Pengaduan
            $table->string('transaksi_layanan')->nullable();
            $table->enum('status', ['Berhasil', 'Tidak Berhasil'])->default('Berhasil');
            $table->string('keterangan_gagal')->nullable();
            $table->enum('suara_pelanggan', ['Puas', 'Tidak puas'])->nullable();
            $table->timestamps();

            $table->index('nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpjs_keliling_participants');
        Schema::table('bpjs_kelilings', function (Blueprint $table) {
            $table->dropColumn(['kedeputian_wilayah', 'kantor_cabang', 'kuadran', 'nama_frontliner', 'lokasi_kegiatan', 'zona_waktu']);
        });
    }
};
