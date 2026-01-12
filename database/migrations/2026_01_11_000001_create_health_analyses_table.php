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
        Schema::create('health_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('analysis_type', ['feces', 'urine']);
            $table->string('image_path');
            $table->json('ai_response')->nullable(); // Respuesta completa de la IA
            $table->json('findings')->nullable(); // Hallazgos procesados
            $table->boolean('requires_attention')->default(false);
            $table->text('recommendations')->nullable();
            $table->float('confidence_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_analyses');
    }
};
