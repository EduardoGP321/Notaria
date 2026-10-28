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
        Schema::create('inmuebles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->cascadeOnDelete();
            $table->foreignId('municipio_id')->constrained('municipios')->restrictOnDelete();
            $table->string('cuenta_predial', 50)->nullable();
            $table->string('clave_catastral', 50)->nullable();
            $table->text('direccion_completa');
            $table->decimal('superficie_terreno_m2', 10, 2)->nullable();
            $table->decimal('superficie_construccion_m2', 10, 2)->nullable();
            $table->text('medidas_colindancias')->nullable();
            $table->string('antecedente_escritura', 100)->nullable();
            $table->string('folio_real_antecedente', 80)->nullable();
            $table->timestamps();

            $table->index('clave_catastral', 'idx_inmuebles_catastral');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inmuebles');
    }
};
