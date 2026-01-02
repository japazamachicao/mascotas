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
        // 1. Profile Photo for Users
        if (!Schema::hasColumn('users', 'profile_photo_path')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_photo_path', 2048)->nullable();
            });
        }

        // 2. Verification Fields for Providers
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
                if (!Schema::hasColumn($table->getTable(), 'verification_document_path')) {
                     $table->string('verification_document_path', 2048)->nullable()->after('id');
                }
                if (!Schema::hasColumn($table->getTable(), 'is_verified')) {
                    $table->boolean('is_verified')->default(false)->after('verification_document_path');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_photo_path');
        });

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
                $table->dropColumn(['verification_document_path', 'is_verified']);
            });
        }
    }
};
