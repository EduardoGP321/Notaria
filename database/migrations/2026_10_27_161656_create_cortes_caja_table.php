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
        Schema::create('cortes_caja', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_corte', ['diario', 'semanal_lunes']);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('total_efectivo', 14, 2)->default(0.00);
            $table->decimal('total_bancos', 14, 2)->default(0.00);
            $table->decimal('monto_total', 14, 2);
            $table->foreignId('usuario_elabora_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['tipo_corte', 'fecha_inicio'], 'idx_cortes_tipo_fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cortes_caja');
    }
};
