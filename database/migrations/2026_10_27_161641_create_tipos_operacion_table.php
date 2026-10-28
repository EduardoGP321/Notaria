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
        Schema::create('tipos_operacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120);
            $table->boolean('requiere_avaluo')->default(true);
            $table->boolean('requiere_rpp')->default(true);
            $table->string('plantilla_word_oficio', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_operacion');
    }
};
