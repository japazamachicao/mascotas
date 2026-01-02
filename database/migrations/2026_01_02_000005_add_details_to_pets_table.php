<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->string('color')->nullable()->after('gender');
            $table->string('chip_id')->nullable()->after('color'); // Importante para identificación
            $table->boolean('is_sterilized')->default(false)->after('chip_id');
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['color', 'chip_id', 'is_sterilized']);
        });
    }
};
