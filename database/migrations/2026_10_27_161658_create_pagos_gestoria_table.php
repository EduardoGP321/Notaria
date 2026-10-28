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
        Schema::create('pagos_gestoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->cascadeOnDelete();
            $table->foreignId('concepto_id')->constrained('conceptos_catalogo')->restrictOnDelete();
            $table->string('linea_captura_referencia', 100);
            $table->string('banco_pago', 80)->nullable();
            $table->decimal('monto_solicitado', 12, 2);
            $table->foreignId('admin_caja_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('gestor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('secretaria_recibe_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_solicitud_recurso');
            $table->date('fecha_entrega_gestor')->nullable();
            $table->date('fecha_pago_dependencia')->nullable();
            $table->date('fecha_factura_secretaria')->nullable();
            $table->string('comprobante_pago_url', 255)->nullable();
            $table->string('factura_catastro_pdf_url', 255)->nullable();
            $table->enum('estatus_pago', [
                'pendiente',
                'recurso_solicitado',
                'entregado_a_gestor',
                'pagado_esperando_factura',
                'factura_con_secretaria'
            ])->default('pendiente');
            $table->timestamps();

            $table->index(['expediente_id', 'estatus_pago'], 'idx_gestoria_exp_estatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_gestoria');
    }
};
