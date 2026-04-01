<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pil_laporan');
        Schema::dropIfExists('pil_kegiatan');

        Schema::create('pil_kegiatan', function (Blueprint $table) {
            $table->id();
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
            
            // Denormalisasi Laporan PIL
            $table->smallInteger('jumlah_peserta')->default(0);
            $table->decimal('rata_uji_pemahaman', 5, 2)->nullable(); // 0.00 - 100.00
            $table->tinyInteger('efek_ketertarikan_jkn')->nullable();  // 1-10
            $table->tinyInteger('efek_rekomendasi_jkn')->nullable();   // 1-10
            $table->tinyInteger('efek_rekomendasi_bpjs')->nullable();  // 1-10
            $table->text('catatan')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tanggal', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pil_kegiatan');
    }
};
