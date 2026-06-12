<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'veterinarians',
            'walkers',
            'groomers',
            'pet_hotels',
            'trainers',
            'pet_sitters',
            'pet_taxis',
            'pet_photographers',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'price_from')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->decimal('price_from', 8, 2)->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'veterinarians', 'walkers', 'groomers', 'pet_hotels',
            'trainers', 'pet_sitters', 'pet_taxis', 'pet_photographers',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('price_from');
            });
        }
    }
};
