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
        Schema::create('operacion_requisitos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_operacion_id')->constrained('tipos_operacion')->cascadeOnDelete();
            $table->foreignId('requisito_id')->constrained('requisitos_catalogo')->cascadeOnDelete();
            $table->boolean('obligatorio')->default(true);
            $table->timestamps();

            $table->unique(['tipo_operacion_id', 'requisito_id'], 'unq_op_requisito');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operacion_requisitos');
    }
};
