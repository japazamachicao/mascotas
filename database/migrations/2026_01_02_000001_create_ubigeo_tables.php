<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Departamentos
        Schema::create('departments', function (Blueprint $table) {
            $table->char('id', 2)->primary(); // Código de departamento (ej: '15' para Lima)
            $table->string('name');
        });

        // Provincias
        Schema::create('provinces', function (Blueprint $table) {
            $table->char('id', 4)->primary(); // Código (ej: '1501' para Lima)
            $table->string('name');
            $table->char('department_id', 2);
            $table->foreign('department_id')->references('id')->on('departments');
        });

        // Distritos
        Schema::create('districts', function (Blueprint $table) {
            $table->char('id', 6)->primary(); // Código (ej: '150101' para Lima)
            $table->string('name');
            $table->char('province_id', 4);
            $table->char('department_id', 2);
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('department_id')->references('id')->on('departments');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('departments');
    }
};
