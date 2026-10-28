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
        Schema::create('citas_agenda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->nullable()->constrained('expedientes')->nullOnDelete();
            $table->foreignId('abogado_id')->constrained('users')->restrictOnDelete();
            $table->string('nombre_interesado', 150);
            $table->enum('tipo_cita', [
                'asesoria_inicial',
                'recepcion_documentos',
                'firma_escritura',
                'entrega_testimonio'
            ]);
            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_fin');
            $table->enum('estatus', [
                'programada',
                'completada',
                'reprogramada',
                'cancelada'
            ])->default('programada');
            $table->timestamps();

            $table->index(['abogado_id', 'fecha_hora_inicio', 'estatus'], 'idx_citas_agenda_abogado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas_agenda');
    }
};
