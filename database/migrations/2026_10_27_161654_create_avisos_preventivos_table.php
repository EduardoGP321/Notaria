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
        Schema::create('avisos_preventivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->cascadeOnDelete();
            $table->enum('tipo_aviso', ['primero', 'segundo'])->default('primero');
            $table->date('fecha_presentacion_rpp');
            $table->date('fecha_vencimiento');
            $table->string('numero_entrada_rpp', 60)->nullable();
            $table->string('documento_formato_url', 255)->nullable();
            $table->enum('estatus', [
                'generado',
                'presentado',
                'autorizado',
                'vencido'
            ])->default('presentado');
            $table->timestamps();

            $table->index(['estatus', 'fecha_vencimiento'], 'idx_avisos_estatus_venc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avisos_preventivos');
    }
};
