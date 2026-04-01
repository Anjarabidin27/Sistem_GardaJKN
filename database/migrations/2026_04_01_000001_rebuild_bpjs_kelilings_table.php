<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old tables to start fresh
        Schema::dropIfExists('bpjs_keliling_laporan');
        Schema::dropIfExists('bpjs_kelilings');

        Schema::create('bpjs_kelilings', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_kegiatan', [
                'goes_to_village', 'around_city', 'goes_to_office',
                'institusi', 'pameran', 'other'
            ]);
            $table->string('judul');
            $table->date('tanggal');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->foreignId('provinsi_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignId('kota_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('kecamatan_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->string('nama_desa')->nullable();
            $table->text('lokasi_detail')->nullable();
            $table->tinyInteger('jumlah_petugas')->default(1);
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            
            // Kolom Hasil Laporan & Transaksi (Denormalisasi)
            $table->smallInteger('layanan_informasi')->default(0);
            $table->smallInteger('layanan_administrasi')->default(0);
            $table->smallInteger('layanan_pengaduan')->default(0);

            $table->smallInteger('transaksi_berhasil')->default(0);
            $table->smallInteger('transaksi_gagal')->default(0);

            $table->smallInteger('jumlah_peserta')->default(0);
            $table->smallInteger('kepuasan_puas')->default(0);
            $table->smallInteger('kepuasan_tidak_puas')->default(0);
            $table->text('catatan')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tanggal', 'status']);
            $table->index('jenis_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bpjs_kelilings');
    }
};
