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
            // Aumentar la precisión del peso por si tenemos mascotas muy pesadas (caballos, vacas)
            // de decimal(5,2) (max 999.99) a decimal(8,2) (max 999999.99)
            $table->decimal('weight', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->decimal('weight', 5, 2)->change();
        });
    }
};
