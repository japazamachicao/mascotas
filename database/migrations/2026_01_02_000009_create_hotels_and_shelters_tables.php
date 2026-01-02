<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla para Hoteles de Mascotas
        Schema::create('pet_hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable(); // Descripción del lugar
            $table->string('address')->nullable();
            $table->char('district_id', 6)->nullable();
            
            // Detalles específicos
            $table->integer('capacity')->default(0); // Capacidad máxima
            $table->boolean('has_transport')->default(false); // Recojo a domicilio
            $table->boolean('cage_free')->default(true); // ¿Libres o en caniles?
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();

            // Redes Sociales
            $table->string('website_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('whatsapp_number')->nullable();

            $table->json('availability')->nullable(); // Horarios de recepción
            $table->timestamps();
        });

        // 2. Tabla para Albergues (Shelters)
        Schema::create('shelters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable(); // Historia del albergue
            $table->string('address')->nullable();
            $table->char('district_id', 6)->nullable();
            
            // Detalles específicos
            $table->integer('capacity')->default(0);
            $table->boolean('accepting_adoptions')->default(true);
            $table->boolean('accepting_volunteers')->default(false);
            $table->boolean('accepting_donations')->default(true);
            $table->text('donation_info')->nullable(); // Cuentas bancarias, Yape, etc.

            // Redes Sociales
            $table->string('website_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('whatsapp_number')->nullable();

            $table->json('availability')->nullable(); // Horarios de visita
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_hotels');
        Schema::dropIfExists('shelters');
    }
};
