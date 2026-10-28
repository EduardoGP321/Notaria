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
        Schema::create('folios_incidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_folio_id')->constrained('lotes_folios')->restrictOnDelete();
            $table->foreignId('escritura_id')->nullable()->constrained('escrituras')->nullOnDelete();
            $table->integer('numero_folio');
            $table->enum('motivo', [
                'error_impresion',
                'hoja_danada_o_rota',
                'escritura_cancelada_no_paso',
                'folio_extraviado'
            ]);
            $table->text('descripcion_detalle')->nullable();
            $table->foreignId('usuario_reporta_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('fecha_incidencia');
            $table->timestamps();

            $table->unique(['lote_folio_id', 'numero_folio'], 'unq_lote_numero_folio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folios_incidencias');
    }
};
