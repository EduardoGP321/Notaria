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

        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->string('folio_presupuesto', 30)->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('abogado_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('tipo_operacion_id')->constrained('tipos_operacion')->restrictOnDelete();
            $table->foreignId('municipio_id')->constrained('municipios')->restrictOnDelete();
            $table->decimal('valor_operacion', 14, 2);
            $table->decimal('porcentaje_aplicado', 5, 2);
            $table->decimal('subtotal_impuestos_der', 12, 2);
            $table->decimal('honorarios_notariales', 12, 2);
            $table->decimal('iva_honorarios', 12, 2)->default(0.00);
            $table->decimal('total_presupuesto', 14, 2);
            $table->enum('estatus', [
                'borrador',
                'entregado',
                'aprobado_con_anticipo',
                'cancelado'
            ])->default('borrador');
            $table->timestamps();

            $table->index(['estatus', 'created_at'], 'idx_presupuestos_estatus_fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
