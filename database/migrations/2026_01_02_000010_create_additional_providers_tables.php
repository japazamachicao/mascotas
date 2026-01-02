<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Adiestradores (Trainers)
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('certification')->nullable(); // Certificaciones
            $table->string('methodology')->nullable(); // Positivo, Mixto, etc.
            $table->boolean('allows_home_visits')->default(true);
            $table->char('district_id', 6)->nullable();
            
            // Redes
            $table->string('website_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('whatsapp_number')->nullable();
            
            $table->json('availability')->nullable();
            $table->timestamps();
        });

        // 2. Pet Sitters (Cuidadores)
        Schema::create('pet_sitters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('housing_type')->nullable(); // Casa, Dpto
            $table->boolean('has_yard')->default(false); // Tiene patio
            $table->boolean('allows_home_visits')->default(true); // Va a casa del dueño
            $table->char('district_id', 6)->nullable();

            // Redes
            $table->string('website_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('whatsapp_number')->nullable();

            $table->json('availability')->nullable();
            $table->timestamps();
        });

        // 3. Pet Taxi
        Schema::create('pet_taxis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('vehicle_type')->nullable(); // Van, Auto, Moto
            $table->boolean('has_ac')->default(false); // Aire acondicionado
            $table->boolean('provides_crate')->default(true); // Tiene jaula
            $table->char('district_id', 6)->nullable(); // Base

             // Redes
             $table->string('website_url')->nullable();
             $table->string('facebook_url')->nullable();
             $table->string('instagram_url')->nullable();
             $table->string('tiktok_url')->nullable();
             $table->string('whatsapp_number')->nullable();

            $table->json('availability')->nullable();
            $table->timestamps();
        });

        // 4. Fotógrafos
        Schema::create('pet_photographers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('specialty')->nullable(); // Estudio, Exteriores, Eventos
            $table->boolean('has_studio')->default(false);
            $table->char('district_id', 6)->nullable();

             // Redes
             $table->string('website_url')->nullable();
             $table->string('facebook_url')->nullable();
             $table->string('instagram_url')->nullable();
             $table->string('tiktok_url')->nullable();
             $table->string('whatsapp_number')->nullable();

            $table->json('availability')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainers');
        Schema::dropIfExists('pet_sitters');
        Schema::dropIfExists('pet_taxis');
        Schema::dropIfExists('pet_photographers');
    }
};
