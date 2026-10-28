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
        Schema::create('recibos_ingresos', function (Blueprint $table) {
            $table->id();
            $table->string('folio_recibo_impreso', 30)->unique();
            $table->foreignId('expediente_id')->constrained('expedientes')->restrictOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('usuario_cobro_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('corte_caja_id')->nullable()->constrained('cortes_caja')->nullOnDelete();
            $table->string('nombre_concepto_recibo', 150);
            $table->decimal('importe', 12, 2);
            $table->enum('forma_pago', ['efectivo', 'transferencia', 'cheque', 'tarjeta']);
            $table->string('banco_referencia', 100)->nullable();
            $table->boolean('impreso_en_foliado')->default(false);
            $table->boolean('requiere_cfdi')->default(false);
            $table->string('uuid_factura_notaria', 50)->nullable();
            $table->dateTime('fecha_pago');
            $table->timestamps();

            $table->index(['fecha_pago', 'corte_caja_id'], 'idx_recibos_fecha_corte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibos_ingresos');
    }
};
