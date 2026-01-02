<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Veterinarios
        Schema::create('veterinarians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('license_number')->nullable(); // Colegiatura
            $table->text('bio')->nullable();
            $table->string('address')->nullable();
            
            // Ubicación
            $table->char('district_id', 6)->nullable();
            $table->foreign('district_id')->references('id')->on('districts');

            $table->boolean('is_verified')->default(false);
            $table->boolean('allows_home_visits')->default(false); // Visitas a domicilio
            $table->timestamps();
        });

        // Paseadores
        Schema::create('walkers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('experience')->nullable();
            $table->char('district_id', 6)->nullable();
            $table->foreign('district_id')->references('id')->on('districts');
            
            $table->decimal('hourly_rate', 8, 2)->default(0);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('walkers');
        Schema::dropIfExists('veterinarians');
        // Agregar aquí los drops para otros proveedores si se añaden
    }
};
