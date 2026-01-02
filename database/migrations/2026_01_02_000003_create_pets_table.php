<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Dueño
            
            $table->string('name');
            $table->string('species'); // Perro, Gato, etc.
            $table->string('breed')->nullable(); // Raza
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable(); // Macho, Hembra
            $table->decimal('weight', 5, 2)->nullable(); // Peso en kg
            
            $table->text('medical_notes')->nullable(); // Alergias, condiciones
            $table->string('profile_photo_path', 2048)->nullable();
            
            // QR Code
            $table->uuid('uuid')->unique(); // Identificador único público para el QR
            $table->string('qr_code_path', 2048)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
