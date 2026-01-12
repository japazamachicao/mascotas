<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modificar el enum para agregar 'skin'
        DB::statement("ALTER TABLE health_analyses MODIFY COLUMN analysis_type ENUM('feces', 'urine', 'skin') NOT NULL");
    }

    public function down(): void
    {
        // Volver al enum original
        DB::statement("ALTER TABLE health_analyses MODIFY COLUMN analysis_type ENUM('feces', 'urine') NOT NULL");
    }
};
