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
        Schema::table('pets', function (Blueprint $table) {
            $table->json('detected_breeds')->nullable()->after('breed'); // Razas detectadas por IA
            $table->float('breed_confidence')->nullable()->after('detected_breeds'); // Confianza en la detección
            $table->json('nutritional_needs')->nullable()->after('breed_confidence'); // Necesidades calculadas
            $table->timestamp('breed_detected_at')->nullable()->after('nutritional_needs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['detected_breeds', 'breed_confidence', 'nutritional_needs', 'breed_detected_at']);
        });
    }
};
