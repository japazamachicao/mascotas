<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
                // pet_id nullable initially as we might not have it strictly required or implemented yet
                $table->foreignId('pet_id')->nullable()->constrained('pets')->onDelete('set null'); 
                $table->dateTime('scheduled_at');
                $table->string('status')->default('pending'); // pending, confirmed, cancelled, completed
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
