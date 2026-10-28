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
        Schema::create('avaluos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->cascadeOnDelete();
            $table->foreignId('perito_id')->constrained('peritos_valuadores')->restrictOnDelete();
            $table->date('fecha_solicitud');
            $table->date('fecha_estimada_entrega');
            $table->date('fecha_recepcion_real')->nullable();
            $table->decimal('valor_catastral', 14, 2)->nullable();
            $table->decimal('valor_comercial', 14, 2)->nullable();
            $table->string('archivo_avaluo_url', 255)->nullable();
            $table->enum('estatus', [
                'solicitado',
                'en_proceso',
                'entregado',
                'observado'
            ])->default('solicitado');
            $table->timestamps();

            $table->index(['estatus', 'fecha_estimada_entrega'], 'idx_avaluos_estatus_entrega');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaluos');
    }
};
