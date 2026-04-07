<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bpjs_kelilings', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kegiatan');
            $table->string('judul');
            $table->date('tanggal');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('kedeputian_wilayah')->nullable();
            $table->string('kantor_cabang')->nullable();
            $table->enum('kuadran', ['1- Engagement', '2- Rekrutmen', '3- Pembaharuan Data', '4- Iuran'])->nullable();
            $table->string('nama_frontliner')->nullable();
            $table->string('lokasi_kegiatan')->nullable();
            $table->string('zona_waktu')->nullable();
            
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
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            
            $table->timestamps();

            $table->index(['tanggal', 'status']);
            $table->index('jenis_kegiatan');
        });

        Schema::create('bpjs_keliling_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bpjs_keliling_id')->constrained('bpjs_kelilings')->cascadeOnDelete();
            $table->string('nik', 16);
            $table->string('name')->nullable();
            $table->string('segmen_peserta')->nullable(); 
            $table->string('phone_number')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('jenis_layanan')->nullable(); 
            $table->string('transaksi_layanan')->nullable();
            $table->enum('status', ['Berhasil', 'Tidak Berhasil'])->default('Berhasil');
            $table->string('keterangan_gagal')->nullable();
            $table->enum('suara_pelanggan', ['Puas', 'Tidak puas'])->nullable();
            $table->timestamps();

            $table->index('nik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bpjs_keliling_participants');
        Schema::dropIfExists('bpjs_kelilings');
    }
};
