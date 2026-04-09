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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('jkn_number', 20)->nullable();
            $table->string('password'); 
            $table->string('name');
            $table->string('role')->default('anggota');
            $table->string('status_pengurus')->default('tidak_mendaftar');
            $table->date('birth_date')->nullable();
            $table->string('phone');
            $table->enum('gender', ['L', 'P']);
            $table->enum('education', ['SD', 'SMP', 'SMA', 'Diploma', 'S1/D4', 'S2']);
            $table->string('occupation');
            $table->string('photo_path')->nullable();
            
            // KTP Address
            $table->text('address_detail');
            $table->foreignId('province_id')->constrained('provinces');
            $table->foreignId('city_id')->constrained('cities');
            $table->foreignId('district_id')->constrained('districts');
            
            // Domicile Address
            $table->foreignId('dom_province_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignId('dom_city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('dom_district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->text('dom_address_detail')->nullable();

            // Pengurus Registration Data
            $table->boolean('is_interested_pengurus')->default(false);
            $table->boolean('has_org_experience')->default(false);
            $table->integer('org_count')->nullable();
            $table->string('org_name')->nullable();
            $table->string('org_position')->nullable();
            $table->integer('org_duration_months')->nullable();
            $table->text('org_description')->nullable();
            $table->text('pengurus_reason')->nullable();
            $table->string('org_certificate_path')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Optional DB indices
            $table->index('name');
            $table->index('status_pengurus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
