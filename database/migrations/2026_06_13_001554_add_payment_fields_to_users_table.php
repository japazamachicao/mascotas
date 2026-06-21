<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('yape_number')->nullable()->after('profile_photo_path');
            $table->string('plin_number')->nullable()->after('yape_number');
            $table->string('yape_qr_path')->nullable()->after('plin_number');
            $table->string('plin_qr_path')->nullable()->after('yape_qr_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['yape_number', 'plin_number', 'yape_qr_path', 'plin_qr_path']);
        });
    }
};
