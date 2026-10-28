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
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_expediente', 30)->unique();
            $table->foreignId('presupuesto_id')->nullable()->constrained('presupuestos')->nullOnDelete();
            $table->foreignId('tipo_operacion_id')->constrained('tipos_operacion')->restrictOnDelete();
            $table->foreignId('institucion_credito_id')->constrained('instituciones_credito')->restrictOnDelete();
            $table->foreignId('municipio_id')->constrained('municipios')->restrictOnDelete();
            $table->foreignId('abogado_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('secretaria_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_apertura');
            $table->date('fecha_limite_sla')->nullable();
            $table->boolean('requiere_factura_hon')->default(false);
            $table->enum('estatus_proceso', [
                'iniciado',
                'aviso_preventivo',
                'en_avaluo',
                'listo_para_firma',
                'firmado_en_folios',
                'pagando_impuestos',
                'engargolado_para_rpp',
                'en_rpp',
                'inscrito',
                'entregado'
            ])->default('iniciado');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['estatus_proceso', 'fecha_limite_sla'], 'idx_exp_estatus_sla');
            $table->index(['fecha_apertura', 'institucion_credito_id'], 'idx_exp_apertura_inst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};
