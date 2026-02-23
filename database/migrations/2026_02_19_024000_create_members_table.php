<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('password'); 
            $table->string('name');
            $table->string('phone');
            $table->enum('gender', ['L', 'P']);
            $table->enum('education', ['SD', 'SMP', 'SMA', 'Diploma', 'S1/D4', 'S2']);
            $table->string('occupation');
            $table->text('address_detail');
            $table->foreignId('province_id')->constrained('provinces');
            $table->foreignId('city_id')->constrained('cities');
            $table->foreignId('district_id')->constrained('districts');
            $table->timestamps();

            // Indexes for optimize
            $table->index('created_at');
            $table->index('gender');
            $table->index('education');
            $table->index('occupation');
            $table->index('province_id');
            $table->index('city_id');
            $table->index('district_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
