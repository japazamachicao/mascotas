<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('care_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->nullable()->constrained()->onDelete('cascade');
            
            // Datos de la mascota en el momento del plan
            $table->json('pet_data'); // nombre, raza, peso, edad, etc.
            
            // Plan completo generado
            $table->json('plan_data'); // todo el plan de cuidado
            
            // Metadata
            $table->string('generation_method')->default('pet'); // 'pet' o 'photo'
            $table->boolean('is_favorite')->default(false);
            
            $table->timestamps();
            
            // Índices
            $table->index(['user_id', 'created_at']);
            $table->index('pet_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('care_plans');
    }
};
