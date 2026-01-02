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
        $providerTables = [
            'veterinarians',
            'walkers',
            'groomers',
            'pet_hotels',
            'shelters',
            'trainers',
            'pet_sitters',
            'pet_taxis',
            'pet_photographers'
        ];

        foreach ($providerTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'verification_attempts')) {
                    $table->tinyInteger('verification_attempts')->default(0)->after('is_verified');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $providerTables = [
            'veterinarians',
            'walkers',
            'groomers',
            'pet_hotels',
            'shelters',
            'trainers',
            'pet_sitters',
            'pet_taxis',
            'pet_photographers'
        ];

        foreach ($providerTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('verification_attempts');
            });
        }
    }
};
