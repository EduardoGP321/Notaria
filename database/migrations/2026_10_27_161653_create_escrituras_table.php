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
        Schema::create('escrituras', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_escritura')->unique();
            $table->foreignId('expediente_id')->unique()->constrained('expedientes')->restrictOnDelete();
            $table->foreignId('lote_folio_id')->constrained('lotes_folios')->restrictOnDelete();
            $table->integer('volumen');
            $table->integer('folio_inicio');
            $table->integer('folio_fin');
            $table->integer('cantidad_folios_usados');
            $table->enum('forma_liquidacion_partes', [
                'efectivo',
                'cheque',
                'transferencia',
                'mixto'
            ])->nullable();
            $table->date('fecha_firma');
            $table->boolean('firmas_completas')->default(false);
            $table->boolean('engargolada_lista')->default(false);
            $table->date('fecha_ingreso_rpp')->nullable();
            $table->date('fecha_inscripcion_rpp')->nullable();
            $table->string('folio_real_rpp', 80)->nullable();
            $table->date('fecha_entrega_cliente')->nullable();
            $table->string('copia_archivo_url', 255)->nullable();
            $table->timestamps();

            $table->index('fecha_firma', 'idx_escrituras_fecha_firma');
            $table->index('fecha_inscripcion_rpp', 'idx_escrituras_inscripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escrituras');
    }
};
