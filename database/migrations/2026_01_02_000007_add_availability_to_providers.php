<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['veterinarians', 'walkers', 'groomers'];
        
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // JSON para almacenar disponibilidad: 
                // { "monday": {"start": "09:00", "end": "18:00", "active": true}, ... }
                $table->json('availability')->nullable(); 
                
                if ($tableName === 'veterinarians') {
                    $table->boolean('emergency_24h')->default(false); // Solo veterinarios suelen tener urgencias 24h reales
                }
            });
        }
    }

    public function down(): void
    {
        $tables = ['veterinarians', 'walkers', 'groomers'];
        
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropColumn('availability');
                if ($tableName === 'veterinarians') {
                    $table->dropColumn('emergency_24h');
                }
            });
        }
    }
};
