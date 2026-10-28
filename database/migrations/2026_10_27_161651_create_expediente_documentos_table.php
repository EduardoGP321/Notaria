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
        Schema::create('expediente_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->cascadeOnDelete();
            $table->foreignId('requisito_id')->constrained('requisitos_catalogo')->restrictOnDelete();
            $table->boolean('entregado_fisico')->default(false);
            $table->string('archivo_digital_url', 255)->nullable();
            $table->dateTime('fecha_recepcion')->nullable();
            $table->timestamps();

            $table->unique(['expediente_id', 'requisito_id'], 'unq_exp_documento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expediente_documentos');
    }
};
