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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->comment('Alias: Casa, Oficina, etc.');
            $table->string('address'); // Dirección exacta
            $table->string('reference')->nullable();
            $table->string('district_id')->nullable(); // Guardamos ID de distrito (string o FK según modelo)
            $table->boolean('is_default')->default(false);
            $table->json('coordinates')->nullable(); // Lat/Lng para mapas futuros
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
