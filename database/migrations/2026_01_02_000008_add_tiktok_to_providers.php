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
                // Para Groomers, necesitamos agregar primero las columnas faltantes si no existen
                if ($tableName === 'groomers') {
                    if (!Schema::hasColumn($tableName, 'website_url')) $table->string('website_url')->nullable();
                    if (!Schema::hasColumn($tableName, 'facebook_url')) $table->string('facebook_url')->nullable();
                    if (!Schema::hasColumn($tableName, 'instagram_url')) $table->string('instagram_url')->nullable();
                    if (!Schema::hasColumn($tableName, 'whatsapp_number')) $table->string('whatsapp_number')->nullable();
                }

                $table->string('tiktok_url')->nullable()->after('instagram_url');
            });
        }
    }

    public function down(): void
    {
        $tables = ['veterinarians', 'walkers', 'groomers'];
        
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('tiktok_url');
            });
        }
    }
};
