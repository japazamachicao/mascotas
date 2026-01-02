<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Nueva tabla para Estilistas (Bañadores/Groomers)
        Schema::create('groomers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('address')->nullable(); // Suelen tener local o van a casa
            $table->char('district_id', 6)->nullable();
            $table->boolean('allows_home_visits')->default(false);
            $table->timestamps();
        });

        // 2. Tabla de Portafolio (Galería de trabajos)
        Schema::create('portfolio_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('image_path', 2048);
            $table->string('title')->nullable(); // Ej: "Corte Schnauzer", "Cirugía Exitosa"
            $table->timestamps();
        });

        // 3. Añadir Link de Redes Sociales y Website a proveedores existentes
        $tables = ['veterinarians', 'walkers'];
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('website_url')->nullable();
                $table->string('facebook_url')->nullable();
                $table->string('instagram_url')->nullable();
                $table->string('whatsapp_number')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_images');
        Schema::dropIfExists('groomers');
        
        $tables = ['veterinarians', 'walkers'];
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn(['website_url', 'facebook_url', 'instagram_url', 'whatsapp_number']);
                });
            }
        }
    }
};
