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
        Schema::create('pils', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('tanggal');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('kedeputian_wilayah')->nullable();
            $table->string('kantor_cabang')->nullable();
            $table->string('nama_frontliner')->nullable();
            $table->string('lokasi_kegiatan')->nullable();
            $table->string('zona_waktu')->default('WIB');
            
            $table->foreignId('provinsi_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignId('kota_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('kecamatan_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->string('nama_desa')->nullable();
            
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            
            // Summaries 
            $table->smallInteger('jumlah_peserta')->default(0);
            $table->decimal('rata_pemahaman', 5, 2)->default(0);
            $table->smallInteger('count_sangat_efektif')->default(0);
            $table->smallInteger('count_efektif')->default(0);
            $table->smallInteger('count_kurang_efektif')->default(0);
            $table->decimal('rata_nps_ketertarikan', 5, 2)->default(0);
            $table->decimal('rata_nps_rekomendasi_program', 5, 2)->default(0);
            $table->decimal('rata_nps_rekomendasi_bpjs', 5, 2)->default(0);
            
            $table->foreignId('created_by')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->timestamps();

            $table->index(['tanggal']);
        });

        Schema::create('pil_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pil_id')->constrained('pils')->cascadeOnDelete();
            $table->string('nik', 16);
            $table->string('name')->nullable();
            $table->string('segmen_peserta')->nullable();
            $table->string('phone_number')->nullable();
            $table->time('jam_sosialisasi_mulai')->nullable();
            $table->time('jam_sosialisasi_selesai')->nullable();
            
            // Specialized PIL Fields
            $table->integer('nilai_pemahaman')->default(0); 
            $table->string('efektifitas_sosialisasi')->nullable(); 
            
            // NPS Scores (1-10)
            $table->tinyInteger('nps_ketertarikan')->default(0);
            $table->tinyInteger('nps_rekomendasi_program')->default(0);
            $table->tinyInteger('nps_rekomendasi_bpjs')->default(0);
            
            $table->timestamps();

            $table->index('nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pil_participants');
        Schema::dropIfExists('pils');
    }
};
