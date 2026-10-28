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
        Schema::create('expediente_comparecientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->enum('rol_compareciente', [
                'comprador',
                'vendedor',
                'acreditado',
                'garante_hipotecario',
                'apoderado',
                'conyuge'
            ]);
            $table->boolean('recibe_factura_catastro')->default(false);
            $table->timestamps();

            $table->unique(['expediente_id', 'cliente_id', 'rol_compareciente'], 'unq_exp_cliente_rol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expediente_comparecientes');
    }
};
