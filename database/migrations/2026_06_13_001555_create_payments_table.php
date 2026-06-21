<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 8, 2);
            $table->string('payment_method'); // 'culqi', 'yape', 'plin'
            $table->string('status')->default('pending'); // 'pending', 'completed', 'failed', 'under_review'
            $table->string('transaction_reference')->nullable();
            $table->string('receipt_photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
