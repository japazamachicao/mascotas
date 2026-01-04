<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // El cliente que da like
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete(); // El proveedor que recibe like
            $table->timestamps();

            // Evitar duplicados
            $table->unique(['user_id', 'provider_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
