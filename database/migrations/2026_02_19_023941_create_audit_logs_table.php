<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('actor_type'); // member/admin
            $table->string('actor_id'); // ID member bisa integer, tapi untuk fleksibilitas bisa string jika UUID
            $table->string('action');
            $table->string('entity_type');
            $table->string('entity_id');
            $table->json('changes_json')->nullable();
            $table->timestamps();

            // Index
            $table->index(['actor_type', 'actor_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
